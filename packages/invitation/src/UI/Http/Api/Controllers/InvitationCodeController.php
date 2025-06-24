<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\UI\Http\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Invitation\Application\Data\InvitationCodeCreateCommand;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Support\UI\Http\Controllers\Controller;

/**
 * 邀请码API控制器
 */
final class InvitationCodeController extends Controller
{
    public function __construct(
        protected InvitationCodeApplicationService $applicationService
    ) {
    }

    /**
     * 创建邀请码
     */
    public function store(Request $request): JsonResponse
    {
        $command = InvitationCodeCreateCommand::from($request->all());
        $invitationCode = $this->applicationService->create($command);

        return $this->success($invitationCode, '创建成功');
    }

    /**
     * 获取邀请码详情
     */
    public function show(string $code): JsonResponse
    {
        $invitationCode = $this->applicationService->findByCode($code);
        
        if (!$invitationCode) {
            return $this->error('邀请码不存在', 404);
        }

        return $this->success($invitationCode);
    }

    /**
     * 使用邀请码
     */
    public function use(string $code): JsonResponse
    {
        try {
            $invitationCode = $this->applicationService->useCode($code);
            return $this->success($invitationCode, '使用成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 生成邀请链接
     */
    public function generateLink(Request $request, string $code): JsonResponse
    {
        $platform = $request->get('platform', 'web');
        $parameters = $request->get('parameters', []);

        try {
            $link = $this->applicationService->generateLink($code, $platform, $parameters);
            return $this->success(['link' => $link]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 分页查询邀请码
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->all();
        $page = (int) $request->get('page', 1);
        $pageSize = (int) $request->get('pageSize', 20);

        $result = $this->applicationService->paginate($filters, $page, $pageSize);
        
        return $this->success($result);
    }

    /**
     * 禁用邀请码
     */
    public function disable(int $id): JsonResponse
    {
        try {
            $this->applicationService->disable($id);
            return $this->success(null, '禁用成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 启用邀请码
     */
    public function enable(int $id): JsonResponse
    {
        try {
            $this->applicationService->enable($id);
            return $this->success(null, '启用成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
} 