<?php
$title='ROPA Master Register'; $clients=$clients??[]; $ropa=$ropa??[]; $template=$template??[]; $definition=$template['definition']??['sections'=>[]];
$value=static fn(string $k):string=>e((string)($ropa[$k]??''));
$selected=static fn(string $k,string $o):string=>(string)($ropa[$k]??'')===$o?'selected':'';
$checked=static function(string $k,string $o)use($ropa):string{$v=$ropa[$k]??[];if(!is_array($v))$v=preg_split('/[,\n]+/',(string)$v);return in_array($o,$v,true)?'selected':'';};
$role=(string)($ropa['ropa_role']??'controller');
$isProcessor=$role==='processor';
$formName=$template['name']??($isProcessor?'ROPA — Processor':'ROPA — Controller');
?>
<div class="page-header mb-4 d-flex justify-content-between align-items-start"><div><div class="page-title"><?= e($formName) ?></div><div class="page-subtitle mb-0">Record and maintain the organisation's Record of Processing Activities.</div></div><div class="d-flex align-items-center gap-2"><a class="btn btn-sm <?= $isProcessor?'btn-outline-secondary':'btn-primary' ?>" href="<?= url('forms/ropa?client_id='.(int)($client['id']??0).'&role=controller') ?>">Controller</a><a class="btn btn-sm <?= $isProcessor?'btn-primary':'btn-outline-secondary' ?>" href="<?= url('forms/ropa?client_id='.(int)($client['id']??0).'&role=processor') ?>">Processor</a><span class="badge text-bg-secondary">Form v<?= (int)($template['version_number']??1) ?></span></div></div>
<form method="post" action="<?= url('forms/ropa/store') ?>" class="card"><?php include __DIR__.'/../partials/csrf.php'; ?><input type="hidden" name="id" value="<?= (int)($ropa['id']??0) ?>"><div class="card-body">
<?php foreach(($definition['sections']??[]) as $section): ?><div class="mb-4"><div class="fw-semibold mb-1"><?= e($section['title']??'Section') ?></div><?php if(!empty($section['description'])):?><div class="text-secondary small"><?= e($section['description']) ?></div><?php endif;?></div><div class="row g-4">
<?php foreach(($section['fields']??[]) as $field): $key=(string)($field['key']??'');$label=(string)($field['label']??$key);$type=(string)($field['type']??'text');$required=!empty($field['required'])?'required':'';$options=$field['options']??[]; ?>
<?php $roles=array_values(array_filter((array)($field['roles']??[])));$visible=!$roles||in_array($role,$roles,true); ?>
<div class="<?= in_array($type,['textarea','multiselect'],true)?'col-12':'col-md-6' ?> ropa-role-field<?= $visible?'':' d-none' ?>" data-ropa-roles="<?= e(implode(' ', $roles)) ?>"><label class="form-label" for="<?= e($key) ?>"><?= e($label) ?> <?= $required?'<span class="text-danger">*</span>':'' ?></label>
<?php if($key==='ropa_role'): ?><input type="hidden" name="ropa_role" value="<?= e($role) ?>"><select class="form-select" id="<?= e($key) ?>" disabled><option value="controller" <?= $role==='controller'?'selected':'' ?>>controller</option><option value="processor" <?= $role==='processor'?'selected':'' ?>>processor</option></select>
<?php elseif($type==='client'): ?><select class="form-select" id="<?= e($key) ?>" name="<?= e($key) ?>" <?= $required ?>><option value="">Select organisation</option><?php foreach($clients as $client):?><option value="<?= (int)$client['id'] ?>" <?= (($ropa[$key]??'')==$client['id'])?'selected':'' ?>><?= e($client['company_name']) ?></option><?php endforeach;?></select>
<?php elseif($type==='auto'): ?><input class="form-control" id="<?= e($key) ?>" value="<?= $key==='record_id' ? e(!empty($ropa['id'])?'ROPA-'.(int)$ropa['id']:'Generated when saved') : ($value($key) ?: e(date('Y-m-d'))) ?>" readonly>
<?php elseif($type==='textarea'): ?><textarea class="form-control" id="<?= e($key) ?>" name="<?= e($key) ?>" rows="3" <?= $required ?>><?= $value($key) ?></textarea>
<?php elseif($type==='select'): ?><select class="form-select" id="<?= e($key) ?>" name="<?= e($key) ?>" <?= $required ?>><option value="">Select</option><?php foreach($options as $option):?><option value="<?= e($option) ?>" <?= $selected($key,(string)$option) ?>><?= e($option) ?></option><?php endforeach;?></select>
<?php elseif($type==='multiselect'): ?><select class="form-select" id="<?= e($key) ?>" name="<?= e($key) ?>[]" multiple size="<?= max(3,min(6,count($options))) ?>" <?= $required ?>><?php foreach($options as $option):?><option value="<?= e($option) ?>" <?= $checked($key,(string)$option) ?>><?= e($option) ?></option><?php endforeach;?></select>
<?php else: ?><input class="form-control" type="<?= in_array($type,['date','number','email'],true)?$type:'text' ?>" id="<?= e($key) ?>" name="<?= e($key) ?>" value="<?= $value($key) ?>" <?= $required ?>><?php endif;?></div>
<?php endforeach;?></div><hr class="my-5"><?php endforeach;?><div class="d-flex justify-content-end gap-2"><a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a><button class="btn btn-primary" type="submit">Save ROPA</button></div></div></form>
<script>
(() => {
    const role = document.getElementById('ropa_role');
    if (!role) return;
    const sync = () => document.querySelectorAll('.ropa-role-field').forEach(field => {
        const roles = (field.dataset.ropaRoles || '').split(' ').filter(Boolean);
        const hidden = roles.length > 0 && !roles.includes(role.value);
        field.classList.toggle('d-none', hidden);
        field.querySelectorAll('input, select, textarea').forEach(control => {
            if (control.id !== 'ropa_role' && control.name !== 'ropa_role') control.disabled = hidden;
        });
    });
    role.addEventListener('change', sync);
    sync();
})();
</script>
