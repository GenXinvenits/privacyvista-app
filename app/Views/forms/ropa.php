<?php
$title='ROPA Master Register'; $clients=$clients??[]; $ropa=$ropa??[]; $template=$template??[]; $definition=$template['definition']??['sections'=>[]];
$value=static fn(string $k):string=>e((string)($ropa[$k]??''));
$selected=static fn(string $k,string $o):string=>(string)($ropa[$k]??'')===$o?'selected':'';
$checked=static function(string $k,string $o)use($ropa):string{$v=$ropa[$k]??[];if(!is_array($v))$v=preg_split('/[,\n]+/',(string)$v);return in_array($o,$v,true)?'checked':'';};
$role=(string)($ropa['ropa_role']??'controller');
$isProcessor=$role==='processor';
$formName=$template['name']??($isProcessor?'ROPA — Processor':'ROPA — Controller');
$showWhen=static function(array $field,array $data):bool{if(empty($field['show_when'])||!is_array($field['show_when']))return true;$source=(string)($field['show_when']['field']??'');$equals=$field['show_when']['equals']??null;$actual=$data[$source]??null;if(is_array($actual))return in_array((string)$equals,array_map('strval',$actual),true);return (string)$actual===(string)$equals;};
$derivedDate=static function(array $field,array $data):string{$existing=trim((string)($data[(string)($field['key']??'')]??''));if($existing!=='')return $existing;$source=(string)($field['derived_from']??'');$base=trim((string)($data[$source]??''));if($base==='')return '';try{$date=new DateTime($base);$date->modify('+' . (int)($field['offset_months']??0) . ' months');return $date->format('Y-m-d');}catch(Throwable $e){return '';}};
?>
<div class="page-header mb-4 d-flex justify-content-between align-items-start"><div><div class="page-title"><?= e($formName) ?></div><div class="page-subtitle mb-0">Record and maintain the organisation's Record of Processing Activities.</div></div><div class="d-flex align-items-center gap-2"><a class="btn btn-sm <?= $isProcessor?'btn-outline-secondary':'btn-primary' ?>" href="<?= url('forms/ropa?client_id='.(int)($client['id']??0).'&role=controller') ?>">Controller</a><a class="btn btn-sm <?= $isProcessor?'btn-primary':'btn-outline-secondary' ?>" href="<?= url('forms/ropa?client_id='.(int)($client['id']??0).'&role=processor') ?>">Processor</a><span class="badge text-bg-secondary">Form v<?= (int)($template['version_number']??1) ?></span></div></div>
<form method="post" action="<?= url('forms/ropa/store') ?>" class="card"><?php include __DIR__.'/../partials/csrf.php'; ?><input type="hidden" name="id" value="<?= (int)($ropa['id']??0) ?>"><div class="card-body">
<?php foreach(($definition['sections']??[]) as $section): ?><div class="mb-4"><div class="fw-semibold mb-1"><?= e($section['title']??'Section') ?></div><?php if(!empty($section['description'])):?><div class="text-secondary small"><?= e($section['description']) ?></div><?php endif;?></div><div class="row g-4">
<?php foreach(($section['fields']??[]) as $field): $key=(string)($field['key']??'');$label=(string)($field['label']??$key);$type=(string)($field['type']??'text');$required=!empty($field['required'])?'required':'';$options=$field['options']??[];$roles=array_values(array_filter((array)($field['roles']??[])));$visible=(!$roles||in_array($role,$roles,true))&&$showWhen($field,$ropa); ?>
<div class="<?= in_array($type,['textarea','multiselect'],true)?'col-12':'col-md-6' ?> ropa-role-field<?= $visible?'':' d-none' ?>" data-ropa-roles="<?= e(implode(' ', $roles)) ?>" data-ropa-condition-field="<?= e((string)($field['show_when']['field']??'')) ?>" data-ropa-condition-equals="<?= e((string)($field['show_when']['equals']??'')) ?>"><label class="form-label" for="<?= e($key) ?>"><?= e($label) ?> <?= $required?'<span class="text-danger">*</span>':'' ?></label>
<?php if($key==='ropa_role'): ?><input type="hidden" name="ropa_role" value="<?= e($role) ?>"><select class="form-select" id="<?= e($key) ?>" disabled><option value="controller" <?= $role==='controller'?'selected':'' ?>>controller</option><option value="processor" <?= $role==='processor'?'selected':'' ?>>processor</option></select>
<?php elseif($type==='client'): ?><select class="form-select" id="<?= e($key) ?>" name="<?= e($key) ?>" <?= $required ?>><option value="">Select organisation</option><?php foreach($clients as $client):?><option value="<?= (int)$client['id'] ?>" <?= (($ropa[$key]??'')==$client['id'])?'selected':'' ?>><?= e($client['company_name']) ?></option><?php endforeach;?></select>
<?php elseif($type==='auto'): ?><input class="form-control" id="<?= e($key) ?>" value="<?= $key==='record_id' ? e(!empty($ropa['id'])?'ROPA-'.(int)$ropa['id']:'Generated when saved') : $value($key) ?>" readonly>
<?php elseif($type==='auto_date'): ?><input class="form-control" id="<?= e($key) ?>" value="<?= $value($key) ?: e(date('Y-m-d')) ?>" readonly>
<?php elseif($type==='derived_date'): ?><input class="form-control" id="<?= e($key) ?>" value="<?= e($derivedDate($field,$ropa)) ?>" readonly><div class="form-text">Automatically calculated from <?= e((string)($field['derived_from']??'the source date')) ?>.</div>
<?php elseif($type==='verification'): ?><div class="input-group"><input class="form-control" id="<?= e($key) ?>" name="<?= e($key) ?>" value="<?= $value($key) ?>" readonly><span class="input-group-text"><span class="badge text-bg-secondary">Automated verification</span></span></div>
<?php elseif($type==='textarea'): ?><textarea class="form-control" id="<?= e($key) ?>" name="<?= e($key) ?>" rows="3" <?= $required ?>><?= $value($key) ?></textarea>
<?php elseif($type==='select'): ?><select class="form-select" id="<?= e($key) ?>" name="<?= e($key) ?>" <?= $required ?>><option value="">Select</option><?php foreach($options as $option):?><option value="<?= e($option) ?>" <?= $selected($key,(string)$option) ?>><?= e($option) ?></option><?php endforeach;?></select>
<?php elseif($type==='multiselect'): ?><div class="ropa-multiselect" id="<?= e($key) ?>" role="group" aria-labelledby="<?= e($key) ?>-label" data-required="<?= $required?'1':'0' ?>"><?php foreach($options as $index=>$option): $optionId=$key.'_'.$index; ?><div class="form-check"><input class="form-check-input ropa-multiselect-option" type="checkbox" id="<?= e($optionId) ?>" name="<?= e($key) ?>[]" value="<?= e($option) ?>" <?= $checked($key,(string)$option) ?>><label class="form-check-label" for="<?= e($optionId) ?>"><?= e($option) ?></label></div><?php endforeach;?></div><?php else: ?><input class="form-control" type="<?= in_array($type,['date','number','email'],true)?$type:'text' ?>" id="<?= e($key) ?>" name="<?= e($key) ?>" value="<?= $value($key) ?>" <?= $required ?>><?php endif;?></div>
<?php endforeach;?></div><hr class="my-5"><?php endforeach;?><div class="d-flex justify-content-end gap-2"><a class="btn btn-outline-secondary" href="<?= url('forms') ?>">Cancel</a><button class="btn btn-primary" type="submit">Save ROPA</button></div></div></form>
<style>
.ropa-multiselect{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.65rem 1.25rem;padding:.25rem 0}.ropa-multiselect .form-check{margin:0!important;min-width:0}.ropa-multiselect .form-check-label{display:block;overflow-wrap:anywhere}@media(max-width:991.98px){.ropa-multiselect{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:575.98px){.ropa-multiselect{grid-template-columns:1fr}}
</style>
<script>
(() => {
    const role = document.getElementById('ropa_role');
    const sync = () => {
        document.querySelectorAll('.ropa-role-field').forEach(field => {
            const roles = (field.dataset.ropaRoles || '').split(' ').filter(Boolean);
            const roleHidden = roles.length > 0 && !roles.includes(role?.value || 'controller');
            const conditionField = field.dataset.ropaConditionField;
            const conditionEquals = field.dataset.ropaConditionEquals;
            let conditionHidden = false;
            if (conditionField) {
                const source = document.getElementById(conditionField);
                const values = source ? (source.matches('select') && source.multiple ? [...source.selectedOptions].map(o => o.value) : [source.value]) : [];
                const checks = document.querySelectorAll(`input[name="${CSS.escape(conditionField)}[]"]:checked`);
                const checkedValues = [...checks].map(c => c.value);
                const allValues = [...values, ...checkedValues];
                conditionHidden = !allValues.includes(conditionEquals);
            }
            const hidden = roleHidden || conditionHidden;
            field.classList.toggle('d-none', hidden);
            field.querySelectorAll('input, select, textarea').forEach(control => {
                if (control.id !== 'ropa_role' && control.name !== 'ropa_role') control.disabled = hidden;
            });
        });
    };
    const syncRequiredMultiSelects = () => document.querySelectorAll('.ropa-multiselect[data-required="1"]').forEach(group => {
        const boxes = [...group.querySelectorAll('.ropa-multiselect-option:not(:disabled)')];
        const updateValidity = () => { const valid = boxes.some(box => box.checked); boxes.forEach(box => box.setCustomValidity(valid ? '' : 'Please select at least one option.')); };
        boxes.forEach(box => box.addEventListener('change', () => { updateValidity(); sync(); })); updateValidity();
    });
    document.querySelectorAll('.ropa-role-field select, .ropa-role-field input').forEach(control => control.addEventListener('change', sync));
    if (role) role.addEventListener('change', sync);
    sync(); syncRequiredMultiSelects();
})();
</script>
