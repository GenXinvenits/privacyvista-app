<?php $title = 'Activities'; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
        <div><h1 class="h3 mb-1">Activities</h1><div class="text-muted">Manage personal-data processing activities by client.</div></div>
        <?php if ($client): ?><a class="btn btn-primary" href="<?= url('processing-activities/create',['client_id'=>(int)$client['id']]) ?>">+ Add activity</a><?php endif; ?>
    </div>
    <?php if ($clients): ?>
    <div class="card mb-4">
        <div class="card-header"><strong>Select client</strong><div class="small text-muted">Choose the organisation whose activities you want to view.</div></div>
        <div class="card-body">
            <select class="form-select" id="activities-client" aria-label="Select client">
                <option value="">Select a client...</option>
                <?php foreach ($clients as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $client && (int)$client['id']===(int)$item['id']?'selected':'' ?>><?= htmlspecialchars($item['company_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($client): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center"><strong>Activities for <?= htmlspecialchars($client['company_name']) ?></strong><span class="badge bg-secondary"><?= count($activities) ?> <?= count($activities)===1?'activity':'activities' ?></span></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Activity</th><th>Department</th><th>Legal basis</th><th>Risk</th><th>Status</th><th class="text-end">Action</th></tr></thead><tbody>
        <?php foreach ($activities as $activity): ?><?php $risk=ucfirst(strtolower((string)($activity['risk_level']??'Low')));$riskClass=$risk==='High'?'danger':($risk==='Medium'?'warning':'success');$status=(int)($activity['status']??0)?'Active':'Draft'; ?><tr><td><div class="fw-semibold"><?= htmlspecialchars($activity['name']) ?></div><?php if(!empty($activity['purpose'])):?><small class="text-muted"><?= htmlspecialchars(mb_strimwidth((string)$activity['purpose'],0,80,'…')) ?></small><?php endif;?></td><td><?= htmlspecialchars($activity['department']?:'—') ?></td><td><?= htmlspecialchars($activity['legal_basis']?:'—') ?></td><td><span class="badge bg-<?= $riskClass ?>"><?= htmlspecialchars($risk) ?></span></td><td><span class="badge bg-<?= $status==='Active'?'success':'secondary' ?>"><?= $status ?></span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= url('processing-activities/edit',['id'=>(int)$activity['id'],'client_id'=>(int)$client['id']]) ?>">Edit</a></td></tr><?php endforeach; ?>
        <?php if(!$activities): ?><tr><td colspan="6" class="text-center py-5"><div class="fw-semibold mb-1">No activities yet</div><div class="text-muted mb-3">Create the first activity for this client.</div><a class="btn btn-sm btn-primary" href="<?= url('processing-activities/create',['client_id'=>(int)$client['id']]) ?>">Add activity</a></td></tr><?php endif; ?>
        </tbody></table></div>
    </div>
    <?php endif; ?>
</div>
<?php if($clients): ?><script>document.getElementById('activities-client')?.addEventListener('change',function(){if(!this.value)return;const u=new URL(window.location.href);u.searchParams.set('route','processing-activities');u.searchParams.set('client_id',this.value);window.location.href=u.toString();});</script><?php endif; ?>
