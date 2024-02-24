<?php

namespace App\Http\Controllers\Setting;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Jeylabs\AuditLog\Models\AuditLog;

/**
 * Class AuditLogController
 * @package App\Http\Controllers\Setting
 */
class AuditLogController extends Controller
{
    /**
     * @return Factory|JsonResponse|View
     */
    public function index()
    {
        $breadcrumb = $this->breadcrumbs(null, 'index');
        if (request()->ajax()) {
            $search = \request()->input('search');
            $activityLogs = AuditLog::orderBy('id', 'desc')->with('causer');
            if ($search) {
                $activityLogs->where(function ($q) use ($search) {
                    $q->where('log_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }
            $activityLogs = $activityLogs->paginate(15);
            $activityLogs->transform(function ($activityLog) {
                $activityLog->action = ucfirst($activityLog->log_name) . ' ' . ucfirst($activityLog->description);
                $activityLog->date = date("F j, Y, g:i a", strtotime($activityLog->created_at));
                return $activityLog;
            });
            return response()->json($activityLogs->toArray());
        }
        return view('settings.audit-log.index', compact('breadcrumb'));
    }

    /**
     * @param AuditLog $log
     * @return Factory|View
     */
    public function show(AuditLog $log)
    {
        $properties = $log->properties->toArray() ?? [];
        $data = [];
        if ($properties) {
            $attributes = array_get($properties, 'attributes') ?? [];
            $old = array_get($properties, 'old') ?? [];
            $newFields = array_keys($attributes) ?? [];
            $oldFields = array_keys($old) ?? [];
            $fields = array_unique(array_merge($oldFields, $newFields));
            if (array_get(array_get($old, 'trans_date', []), 'date')) {
                $old['trans_date'] = carbon(array_get($old, 'trans_date')['date'])->toDateString();
            }
            $data['attributes'] = $attributes;
            $data['old'] = $old;
            $data['fields'] = $fields;
        }
        $breadcrumb = $this->breadcrumbs($log, 'show');
        return view('settings.audit-log.show', compact('breadcrumb', 'log', 'data'));
    }

    /**
     * @param AuditLog|null $report
     * @param string|null $method
     * @return array
     */
    public function breadcrumbs(AuditLog $report = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings', 'route' => 'setting.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Audit Logs'],
            ]),

            'show' => array_merge($base, [
                ['text' => 'Audit logs', 'route' => 'setting.audit.log.index'],
                ['text' => 'Details'],
            ]),
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}
