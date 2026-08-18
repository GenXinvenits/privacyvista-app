<?php
$title = 'Client Management';
use App\Core\Security;
component('page-header', ['title'=>'Clients','subtitle'=>'Manage client companies','actions'=>[['label'=>'+ Add Client','url'=>url('clients/create'),'class'=>'btn-primary']]]);
?>
<div class="card"><div class="card-body">
<div class="row mb-3"><div class="col-md-4"><input type="text" class="form-control" placeholder="Search clients..." id="clientSearch"></div></div>
<div class="table-responsive"><table class="table table-hover align-middle" id="clientsTable">
<thead class="table-light"><tr><th>ID</th><th>Company</th><th>Contact Person</th><th>Email</th><th>Phone</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php if(empty($clients)): ?><tr><td colspan="7" class="text-center text-muted">No clients found.</td></tr>
<?php else: foreach($clients as $client): ?><tr>
<td><?= (int)$client['id'] ?></td><td><?= e($client['company_name']) ?></td><td><?= e($client['contact_person']) ?></td><td><?= e($client['email']) ?></td><td><?= e($client['phone']) ?></td>
<td><?= $client['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
<td class="text-nowrap"><a href="<?= url('clients/show',['id'=>$client['id']]) ?>" class="btn btn-sm btn-info">View</a> <a href="<?= url('clients/edit',['id'=>$client['id']]) ?>" class="btn btn-sm btn-warning">Edit</a>
<form method="post" action="<?= url('clients/delete') ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client?');"><?php include __DIR__.'/../partials/csrf.php'; ?><input type="hidden" name="id" value="<?= (int)$client['id'] ?>"><button class="btn btn-sm btn-danger">Delete</button></form></td>
</tr><?php endforeach; endif; ?>
</tbody></table></div></div></div>
<script>document.getElementById('clientSearch')?.addEventListener('input',function(){const q=this.value.toLowerCase();document.querySelectorAll('#clientsTable tbody tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'':'none');});</script>