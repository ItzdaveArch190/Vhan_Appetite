<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $getProducts = $con->getAllProducts();
    $getCategory = $con->viewCategory();

    // helper to find uploaded image relative path
    function findProductImageRel_view($id){
        $base = __DIR__ . '/../uploads/products/product_'.intval($id).'.*';
        $matches = glob($base);
        if(!$matches) return '';
        $file = $matches[0];
        $rel = str_replace(__DIR__ . '/../', '../', $file);
        return $rel;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Products</title>
    <style>
        .sidebar{
            width: 350px;
            position:fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow: hidden; 
        }
        
        .owner-name{
            font-family:'Brush Script MT', 'Brush Script Std', cursive;
            font-size: 20px;
        }
        .custom-btn{
            background-color:#E69B1A;
            height: 50px;
        }
        .custom-btn:hover{
            background-color:#BC7F15;
        }
        main{
            margin-left: 360px;
            margin-top:40px;
            height: 100vh;
            overflow-y:auto;
        }
    </style>
</head>
<body>
    <div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

<main>
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-0">Products</h2>
                <p class="text-muted small">Browse and review all products in the catalog.</p>
            </div>
            <div class="col-md-4 d-flex justify-content-end align-items-center gap-2">
                <input id="vpSearch" class="form-control form-control-sm" placeholder="Search products...">
                <select id="vpFilterCat" class="form-select form-select-sm w-auto">
                    <option value="">All</option>
                    <?php foreach($getCategory as $c): ?>
                        <option value="<?php echo $c['Category_ID']; ?>"><?php echo htmlspecialchars($c['Category_Name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row g-3" id="vpGrid"></div>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                <nav><ul class="pagination" id="vpPagination"></ul></nav>
            </div>
        </div>
    </div>
</main>
    </div>
</body>
<script src="../functions/window.js"></script>
<script>
    (function(){
        const search = document.getElementById('vpSearch');
        const filter = document.getElementById('vpFilterCat');
        const grid = document.getElementById('vpGrid');
        const pagination = document.getElementById('vpPagination');
        let currentPage = 1; const perPage = 12;

        function renderItems(items){
            grid.innerHTML = '';
            items.forEach(function(p){
                const col = document.createElement('div'); col.className = 'col-6 col-md-4 col-lg-3 vp-card';
                col.setAttribute('data-name', (p.Product_Name||'').toLowerCase());
                col.setAttribute('data-cat', p.Category_ID || '');
                col.innerHTML = `
                    <div class="card h-100 shadow-sm">
                        ${p.image? `<img src="${p.image}" class="card-img-top" style="height:160px;object-fit:cover;">` : '<div class="card-img-top d-flex align-items-center justify-content-center" style="height:160px;background:#f5f5f5;">No image</div>'}
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">${escapeHtml(p.Product_Name)}</h6>
                            <div class="small text-muted mb-2">${escapeHtml(p.Category_Name||'-')}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="fw-bold">₱${Number(p.Product_Price||0).toFixed(2)}</div>
                                <div class="small text-muted">Stock: ${p.Stock}</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-2 d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-outline-primary vp-manage-btn" data-id="${p.Product_ID}">Manage</button>
                        </div>
                    </div>`;
                grid.appendChild(col);
            });
            // attach manage btns
            document.querySelectorAll('.vp-manage-btn').forEach(function(btn){
                btn.addEventListener('click', function(){
                    const id = this.getAttribute('data-id');
                    openEditModalById(id);
                });
            });
        }

        function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

        function fetchPage(page){
            const q = encodeURIComponent(search?.value || '');
            const cat = encodeURIComponent(filter?.value || '');
            fetch('getProducts.php?page='+page+'&per_page='+perPage+'&search='+q+'&category='+cat)
                .then(r=>r.json())
                .then(function(data){
                    if(!data.ok) return;
                    renderItems(data.items);
                    renderPagination(data.page, data.per_page, data.total);
                }).catch(console.error);
        }

        function renderPagination(page, per, total){
            const pages = Math.max(1, Math.ceil(total / per));
            pagination.innerHTML = '';
            const prev = document.createElement('li'); prev.className='page-item'+(page<=1?' disabled':''); prev.innerHTML = `<a class="page-link" href="#">«</a>`;
            pagination.appendChild(prev);
            prev.addEventListener('click', function(e){ e.preventDefault(); if(page>1) { fetchPage(page-1);} });
            for(let i=1;i<=pages;i++){
                const li = document.createElement('li'); li.className = 'page-item'+(i===page?' active':''); li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', function(e){ e.preventDefault(); fetchPage(i); });
                pagination.appendChild(li);
            }
            const next = document.createElement('li'); next.className='page-item'+(page>=pages?' disabled':''); next.innerHTML = `<a class="page-link" href="#">»</a>`;
            next.addEventListener('click', function(e){ e.preventDefault(); if(page<pages) fetchPage(page+1); });
            pagination.appendChild(next);
        }

        if(search) search.addEventListener('input', function(){ fetchPage(1); });
        if(filter) filter.addEventListener('change', function(){ fetchPage(1); });

        // initial load
        fetchPage(1);

        // function to open edit modal by product id (loads product details via getProducts API)
        function openEditModalById(id){
            // find the product in currently displayed cards first
            // else request page 1.. we can fetch a single product by page load with search
            fetch('getProducts.php?page=1&per_page=1&search=&category=&id='+encodeURIComponent(id))
                .then(r=>r.json()).then(function(data){
                    let p = null;
                    if(data.ok && data.items && data.items.length){ p = data.items[0]; }
                    // fallback: try to extract from DOM
                    if(!p){
                        const el = document.querySelector('.vp-card [data-id="'+id+'"]').closest('.vp-card');
                    }
                    if(!p){ alert('Product details not found'); return; }
                    // populate edit modal fields (modal exists on ManageMenu page; we'll create a local modal here)
                    // Build and show a lightweight modal to edit by submitting AJAX to ManageMenu.php
                    showEditModalInline(p);
                }).catch(console.error);
        }

        // Build inline edit modal (simpler than reusing ManageMenu modal)
        function showEditModalInline(p){
            // create modal if not present
            let modal = document.getElementById('vpInlineEditModal');
            if(!modal){
                modal = document.createElement('div'); modal.id='vpInlineEditModal'; modal.className='modal fade'; modal.tabIndex='-1'; modal.innerHTML = `
                <div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="vpEditForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit_product_id" id="vp_edit_product_id">
                        <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="edit_product_name" id="vp_edit_product_name" required></div>
                        <div class="row g-2"><div class="col-md-4"><label class="form-label">Price</label><input class="form-control" name="edit_product_price" id="vp_edit_product_price" required></div>
                        <div class="col-md-4"><label class="form-label">Stock</label><input class="form-control" name="edit_product_stock" id="vp_edit_product_stock" required></div>
                        <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="edit_product_status" id="vp_edit_product_status"><option>Available</option><option>Unavailable</option></select></div></div>
                        <div class="mb-3 mt-3"><label class="form-label">Category</label><select class="form-select" name="edit_product_category" id="vp_edit_product_category" required><option value="">Select</option><?php foreach($getCategory as $c){ echo '<option value="'.$c['Category_ID'].'">'.htmlspecialchars($c['Category_Name']).'</option>'; } ?></select></div>
                        <div class="mb-3"><label class="form-label">Replace Image</label><input type="file" class="form-control" name="edit_product_image"></div>
                    </form>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button id="vp_save_edit" class="btn btn-primary">Save</button></div>
                </div></div></div>`;
                document.body.appendChild(modal);
            }
            // populate
            document.getElementById('vp_edit_product_id').value = p.Product_ID;
            document.getElementById('vp_edit_product_name').value = p.Product_Name;
            document.getElementById('vp_edit_product_price').value = Number(p.Product_Price||0).toFixed(2);
            document.getElementById('vp_edit_product_stock').value = p.Stock;
            document.getElementById('vp_edit_product_status').value = p.Status || 'Available';
            document.getElementById('vp_edit_product_category').value = p.Category_ID || '';

            const bsModal = new bootstrap.Modal(document.getElementById('vpInlineEditModal'));
            bsModal.show();

            document.getElementById('vp_save_edit').onclick = function(){
                const form = document.getElementById('vpEditForm');
                const fd = new FormData(form);
                fd.append('ajax','1');
                fetch('ManageMenu.php', { method:'POST', body: fd }).then(r=>r.json()).then(function(res){
                    if(res.ok){ bsModal.hide(); fetchPage(currentPage); } else { alert(res.error || 'Save failed'); }
                }).catch(function(e){ alert('Save failed'); console.error(e); });
            };
        }

    })();
</script>
</html>