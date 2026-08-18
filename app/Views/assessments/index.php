<?php $title='Assessments'; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
        <div><h1 class="h3 mb-1">Assessments</h1><div class="text-muted">Privacy assessments and risk reviews</div></div>
        <?php if ($client): ?><a class="btn btn-primary" href="<?= url('assessments/create',['client_id'=>(int)$client['id']]) ?>">New assessment</a><?php endif; ?>
    </div>

    <?php if ($clients): ?>
    <div class="card mb-4">
        <div class="card-header"><strong>Select client</strong><div class="small text-muted">Choose the organisation whose assessments you want to view.</div></div>
        <div class="card-body">
            <select class="form-select" id="assessment-client" aria-label="Select client">
                <option value="">Select a client...</option>
                <?php foreach ($clients as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $client && (int)$client['id']===(int)$item['id']?'selected':'' ?>><?= e($item['company_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($client): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center"><strong>Assessments for <?= e($client['company_name']) ?></strong><span class="badge bg-primary"><?= count($assessments) ?> assessments</span></div>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Risk</th><th>Due</th><th>Owner</th><th></th></tr></thead><tbody>
        <?php foreach ($assessments as $a): ?><tr><td class="fw-semibold"><?= e($a['title']) ?></td><td><?= e(ucwords(str_replace('_',' ',$a['assessment_type']))) ?></td><td><?= e(ucwords(str_replace('_',' ',$a['status']))) ?></td><td><?= e((string)($a['risk_score']??'—')) ?><?php if($a['risk_score']!==null):?>/100<?php endif;?></td><td><?= e($a['due_date']??'—') ?></td><td><?= e($a['owner_name']??'—') ?></td><td><a class="btn btn-sm btn-outline-secondary" href="<?= url('assessments/edit',['id'=>(int)$a['id'],'client_id'=>(int)$client['id']]) ?>">Edit</a></td></tr><?php endforeach; ?>
        <?php if (!$assessments): ?><tr><td colspan="7" class="text-center text-muted py-5">No assessments yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </div>
    <?php endif; ?>
</div>
<?php if ($clients): ?><script>document.getElementById('assessment-client')?.addEventListener('change',function(){if(!this.value)return;const u=new URL(window.location.href);u.searchParams.set('route','assessments');u.searchParams.set('client_id',this.value);window.location.href=u.toString();});</script><?php endif; ?>
