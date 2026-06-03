<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $getProducts = $con->getAllProducts();
    $getCategory = $con->viewCategory();
    $totalProducts = count($getProducts);
    $totalCategories = count($getCategory);
    $activeProducts = 0;
    $totalStock = 0;
    foreach($getProducts as $product){
        $totalStock += (int)($product['Stock'] ?? 0);
        if((int)($product['Status'] ?? 0) === 1 || strtolower((string)($product['Status'] ?? '')) === 'available'){
            $activeProducts++;
        }
    }

    // helper to find uploaded image relative path
    function findProductImageRel_view($id){
        $base = __DIR__ . '/../uploads/products/product_'.intval($id).'.*';
        $matches = glob($base);
        if(!$matches) return '';
        $file = $matches[0];
        $rel = str_replace(__DIR__ . '/../', '', $file);
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
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .sidebar{
            width: 350px;
            position:fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow: hidden; 
        }

        .products-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .products-hero{
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .products-hero__body{
            padding: 30px 32px;
        }

        .products-kicker{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.22);
            color: #e6fff0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .products-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
        }

        .products-copy{
            color: rgba(255,255,255,.84);
            max-width: 720px;
        }

        .summary-card,
        .table-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .summary-metric{
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e7ece3;
            padding: 16px;
        }

        .summary-metric .label{
            color: #6b7280;
            font-size: .76rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .summary-metric .value{
            margin-top: 4px;
            color: #1f2937;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .toolbar-card{
            border: 0;
            border-radius: 22px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .filter-chip{
            border: 1px solid #dbe5d5;
            background: #fff;
            color: #1f2937;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 0.88rem;
            transition: all .2s ease;
        }

        .filter-chip.active,
        .filter-chip:hover{
            background: #0f8a52;
            color: #fff;
            border-color: #0f8a52;
        }

        .product-card{
            border: 0;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            transition: transform .2s ease, box-shadow .2s ease;
            background: #fff;
            height: 100%;
        }

        .product-card:hover{
            transform: translateY(-3px);
            box-shadow: 0 18px 44px rgba(15, 23, 42, 0.12);
        }

        .product-card__image{
            height: 200px;
            object-fit: cover;
            width: 100%;
            background: #eef2ec;
        }

        .product-card__body{
            padding: 16px;
        }

        .product-card__title{
            margin-bottom: 6px;
            font-size: 1.02rem;
            font-weight: 800;
            color: #1f2937;
        }

        .product-card__meta{
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .product-badge{
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.08);
            color: #0d6b42;
            font-weight: 700;
            font-size: .82rem;
        }

        .product-price{
            font-size: 1.12rem;
            font-weight: 800;
            color: #111827;
        }

        .product-stock{
            font-size: .88rem;
            color: #6b7280;
        }

        .product-actions{
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .product-actions .btn{
            border-radius: 12px;
            font-weight: 700;
        }

        .empty-state{
            border: 1px dashed #d6dfd2;
            border-radius: 22px;
            background: #fbfcfa;
            padding: 40px 20px;
            text-align: center;
            color: #6b7280;
        }

        .section-label{
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #0d6b42;
        }

        .search-box{
            min-width: 260px;
        }

        @media (max-width: 991px){
            .products-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }

            .search-box{
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

<main class="products-shell w-100">
    <div class="card products-hero mb-4">
        <div class="products-hero__body">
            <span class="products-kicker">Product Catalog</span>
            <h1 class="products-title">Browse, manage, and update your menu</h1>
            <p class="products-copy mb-0">Review the catalog in a cleaner layout with live search, category filtering, and fast edit access for each item.</p>
        </div>
    </div>

    <div class="card summary-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <div class="summary-metric">
                        <div class="label">Products</div>
                        <div class="value"><?php echo $totalProducts; ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="summary-metric">
                        <div class="label">Categories</div>
                        <div class="value"><?php echo $totalCategories; ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="summary-metric">
                        <div class="label">Active Items</div>
                        <div class="value"><?php echo $activeProducts; ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="summary-metric">
                        <div class="label">Total Stock</div>
                        <div class="value"><?php echo $totalStock; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card toolbar-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div>
                    <div class="section-label mb-2">Catalog Controls</div>
                    <h5 class="mb-0">Search and filter products</h5>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <input id="vpSearch" class="form-control search-box" placeholder="Search products...">
                    <select id="vpFilterCat" class="form-select" style="min-width: 190px;">
                        <option value="">All Categories</option>
                        <?php foreach($getCategory as $c): ?>
                            <option value="<?php echo $c['Category_ID']; ?>"><?php echo htmlspecialchars($c['Category_Name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap mb-3" id="vpCategoryChips">
                <button type="button" class="btn filter-chip active" data-cat="">All</button>
                <?php foreach($getCategory as $c): ?>
                    <button type="button" class="btn filter-chip" data-cat="<?php echo $c['Category_ID']; ?>"><?php echo htmlspecialchars($c['Category_Name']); ?></button>
                <?php endforeach; ?>
            </div>

            <div class="row g-3" id="vpGrid"></div>
            <div class="empty-state mt-3 d-none" id="vpEmptyState">
                <h6 class="mb-1">No products found</h6>
                <div>Try a different search term or category filter.</div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            <nav><ul class="pagination" id="vpPagination"></ul></nav>
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
        const emptyState = document.getElementById('vpEmptyState');
        const chips = Array.from(document.querySelectorAll('#vpCategoryChips .filter-chip'));
        let currentPage = 1;
        const perPage = 12;

        function setEmptyState(show){
            if(!emptyState) return;
            emptyState.classList.toggle('d-none', !show);
        }

        function renderItems(items){
            grid.innerHTML = '';
            setEmptyState(!items || !items.length);
            items.forEach(function(p){
                const col = document.createElement('div'); col.className = 'col-6 col-md-4 col-lg-3 vp-card';
                col.setAttribute('data-name', (p.Product_Name||'').toLowerCase());
                col.setAttribute('data-cat', p.Category_ID || '');
                col.innerHTML = `
                    <div class="product-card">
                        ${p.image? `<img src="${p.image}" class="product-card__image" alt="${escapeHtml(p.Product_Name)}">` : '<div class="product-card__image d-flex align-items-center justify-content-center text-muted">No image</div>'}
                        <div class="product-card__body">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div>
                                    <div class="product-card__title">${escapeHtml(p.Product_Name)}</div>
                                    <div class="small text-muted">${escapeHtml(p.Category_Name||'-')}</div>
                                </div>
                                <span class="product-badge">${escapeHtml(String(p.Status || 'Active'))}</span>
                            </div>

                            <div class="product-card__meta">
                                <div>
                                    <div class="product-price">₱${Number(p.Product_Price||0).toFixed(2)}</div>
                                    <div class="product-stock">Stock: ${Number(p.Stock||0)}</div>
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-outline-primary vp-manage-btn" data-id="${p.Product_ID}">Edit</button>
                                </div>
                            </div>
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
            currentPage = page;
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
        if(filter) filter.addEventListener('change', function(){
            chips.forEach(function(chip){
                chip.classList.toggle('active', chip.getAttribute('data-cat') === (filter.value || ''));
            });
            fetchPage(1);
        });

        chips.forEach(function(chip){
            chip.addEventListener('click', function(){
                const cat = this.getAttribute('data-cat') || '';
                if(filter) filter.value = cat;
                chips.forEach(function(btn){ btn.classList.toggle('active', btn === chip); });
                fetchPage(1);
            });
        });

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
                    <form id="vpEditForm" enctype="multipart/form-data">
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