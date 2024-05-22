<?php

namespace App\Controller\Api\Task;

use App\Dto\Task\CreateTaskDto;
use App\Dto\Task\EditTaskDto;
use App\Dto\Task\TaskDto;
use App\Entity\Task;
use App\Entity\User;
use App\Exception\AccessDeniedException;
use App\Exception\AllTaskMustBeCompletedException;
use App\Exception\TaskCompletedException;
use App\Service\Task\TaskCompleter;
use App\Service\Task\TaskCreator;
use App\Service\Task\TaskDeleter;
use App\Service\Task\TaskEditor;
use App\Service\Task\TaskGetter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Task')]
class TaskApiController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route(
        path: '/api/task/',
        methods: [Request::METHOD_GET],
    )]
    #[OA\Get(path: '/api/task/', summary: "Get user's tasks")]
    #[OA\Parameter(
        name: 'title',
        description: 'Filter by title',
        in: 'query',
        required: false,
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Filter by description',
        in: 'query',
        required: false,
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Filter by priority',
        in: 'query',
        required: false,
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Filter by status',
        in: 'query',
        required: false,
    )]
    #[OA\Parameter(
        name: 'sortBy',
        description: 'Sort by some field. Format: field_name desc/asc. Available sorting by: completedAt, createdAt, priority',
        in: 'query',
        required: false,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'All is ok',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: TaskDto::class))),
    )]
    public function get(
        Request $request,
        #[CurrentUser] User $user,
        TaskGetter $taskGetter,
    ): Response {
        return new JsonResponse($this->serializer->serialize(
            $taskGetter->get($user, $request->query->all()),
            'json',
        ), json: true);
    }

    #[Route(
        path: '/api/task/',
        methods: [Request::METHOD_POST],
    )]
    #[OA\Post(path: '/api/task/', summary: 'Create task')]
    #[OA\RequestBody(
        description: 'Task data',
        required: true,
        content: new OA\JsonContent(ref: new Model(type: CreateTaskDto::class)),
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Task created successfully',
        content: new OA\JsonContent(ref: new Model(type: TaskDto::class)),
    )]
    public function create(
        Request $request,
        #[CurrentUser] User $user,
        TaskCreator $taskCreator,
    ): Response {
        $requestDto = $this->serializer->deserialize($request->getContent(), CreateTaskDto::class, 'json');

        return new JsonResponse($this->serializer->serialize(
            $taskCreator->create($user, $requestDto),
            'json',
        ), json: true);
    }

    /**
     * @throws AccessDeniedException
     */
    #[Route(
        path: '/api/task/{taskId}/',
        methods: [Request::METHOD_PUT],
    )]
    #[OA\Put(path: '/api/task/{taskId}/', summary: 'Edit task')]
    #[OA\RequestBody(
        description: 'Task data',
        required: true,
        content: new OA\JsonContent(ref: new Model(type: EditTaskDto::class)),
    )]
    #[OA\Parameter(
        name: 'taskId',
        description: 'Task ID',
        in: 'path',
        required: true,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Task edited successfully',
        content: new OA\JsonContent(ref: new Model(type: TaskDto::class)),
    )]
    #[OA\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'U dont have access to this task',
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Task not found',
    )]
    public function edit(
        Request $request,
        #[CurrentUser] User $user,
        #[MapEntity(id: 'taskId')] Task $task,
        TaskEditor $taskEditor,
    ): Response {
        $requestDto = $this->serializer->deserialize($request->getContent(), EditTaskDto::class, 'json');

        return new JsonResponse($this->serializer->serialize(
            $taskEditor->edit($task, $user, $requestDto),
            'json',
        ), json: true);
    }

    /**
     * @throws AccessDeniedException
     * @throws AllTaskMustBeCompletedException
     */
    #[Route(
        path: '/api/task/{taskId}/complete/',
        methods: [Request::METHOD_PATCH],
    )]
    #[OA\Patch(path: '/api/task/{taskId}/complete/', summary: 'Complete task')]
    #[OA\Parameter(
        name: 'taskId',
        description: 'Task ID',
        in: 'path',
        required: true,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Task completed',
        content: new OA\JsonContent(ref: new Model(type: TaskDto::class)),
    )]
    #[OA\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'U dont have access to this task',
    )]
    #[OA\Response(
        response: Response::HTTP_CONFLICT,
        description: "U can't complete task, if not all subtasks are completed",
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Task not found',
    )]
    public function complete(
        #[CurrentUser] User $user,
        #[MapEntity(id: 'taskId')] Task $task,
        TaskCompleter $taskCompleter,
    ): Response {
        return new JsonResponse($this->serializer->serialize(
            $taskCompleter->complete($user, $task),
            'json',
        ), json: true);
    }

    /**
     * @throws TaskCompletedException
     * @throws AccessDeniedException
     */
    #[Route(
        path: '/api/task/{taskId}/',
        methods: [Request::METHOD_DELETE],
    )]
    #[OA\Delete(path: '/api/task/{taskId}/', summary: 'Delete task')]
    #[OA\Parameter(
        name: 'taskId',
        description: 'Task ID',
        in: 'path',
        required: true,
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Task deleted',
    )]
    #[OA\Response(
        response: Response::HTTP_FORBIDDEN,
        description: 'U dont have access to this task',
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "U can't delete task, if it is completed",
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Task not found',
    )]
    public function delete(
        #[CurrentUser] User $user,
        #[MapEntity(id: 'taskId')] Task $task,
        TaskDeleter $taskDeleter,
    ): Response {
        $taskDeleter->delete($task, $user);

        return new JsonResponse();
    }
}
