<form id="addCategoryForm" method="POST" action="<?php echo e(route('classificationRead')); ?>">
    <?php echo csrf_field(); ?>

    <div class="input-group input-group-sm">
        <input type="text" name="name" class="form-control" placeholder="Enter new category" required>

        <div class="input-group-append">
            <div class="btn-group">
                <!-- Dropdown button shows the selected icon -->
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i id="selectedIconPreview" class="<?php echo e(old('icon', 'fas fa-question')); ?>"></i>
                </button>

                <!-- Dropdown menu: a scrollable grid/list of <i> icons -->
                <div class="dropdown-menu dropdown-menu-right p-2" style="width:260px; max-height:220px; overflow:auto;">
                    <!-- Put whatever icons you want here -->
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-glass-martini-alt" title="Beverages"><i class="fas fa-glass-martini-alt fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-shopping-basket" title="Grocery"><i class="fas fa-shopping-basket fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-utensils" title="Cooked Dish / Meal"><i class="fas fa-utensils fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-box" title="Snack"><i class="fas fa-box fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-mug-hot" title="Coffee"><i class="fas fa-mug-hot fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-ice-cream" title="Ice Cream"><i class="fas fa-ice-cream fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-blender" title="Appliances"><i class="fas fa-blender fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-desktop" title="IT Supplies"><i class="fas fa-desktop fa-lg"></i></a>
                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-plug" title="Electronics"><i class="fas fa-plug fa-lg"></i></a>
                </div>
            </div>

            <!-- submit button -->
            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
        </div>
    </div>

    <!-- hidden input to actually submit the chosen icon class -->
    <input type="hidden" name="icon" id="selectedIcon" value="<?php echo e(old('icon', '')); ?>">
</form>

<!-- small styles to make the menu look like an icon grid -->
<style>
    .icon-picker-item { display: inline-flex; width: 64px; height: 48px; align-items: center; justify-content: center; padding: .35rem; }
    .icon-picker-item:hover, .icon-picker-item.active { background: rgba(0,0,0,0.04); }
    #selectedIconPreview { min-width: 20px; display:inline-block; text-align:center; }
</style>

<script>
    $(function(){
        // click handler for items
        $('.icon-picker-item').on('click', function(e){
            e.preventDefault();
            var cls = $(this).data('value');

            // set hidden input and update preview
            $('#selectedIcon').val(cls);
            $('#selectedIconPreview').attr('class', cls);

            // mark active
            $('.icon-picker-item').removeClass('active');
            $(this).addClass('active');

            // close dropdown (Bootstrap)
            $(this).closest('.dropdown').find('[data-toggle="dropdown"]').dropdown('toggle');
        });

        // initialize active item if value exists (for edit/old)
        var init = $('#selectedIcon').val();
        if (init) {
            var $found = $('.icon-picker-item[data-value="'+init+'"]');
            if ($found.length) {
                $found.addClass('active');
                $('#selectedIconPreview').attr('class', init);
            }
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/script/classificationScript.blade.php ENDPATH**/ ?>