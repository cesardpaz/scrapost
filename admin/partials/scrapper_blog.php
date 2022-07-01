<?php 
/* List post types */
$postTypesInit = ['post', 'page'];
$argsPostType = array(
    'public'   => true,
    '_builtin' => false
);
$post_types_custom =  array_values(get_post_types($argsPostType));
$postTypesList = array_merge($postTypesInit, $post_types_custom);

/* List Taxonomies */
$taxInit = ['category', 'post_tag'];
$argsTaxs = array(
    'public'   => true,
    '_builtin' => false
);
$taxCustom = array_values(get_taxonomies($argsTaxs));
$taxList = array_merge($taxInit, $taxCustom);
?>


<div class="container-fluid">

    <div class="row">

        <div class="col-12">
            <h2>Generate post for blog</h2>
        </div>

        <div class="col-4">
            <form id="form_generate_blog">

                <div class="mb-3">
                    <label for="number_scrapost" class="form-label">Number of post</label>
                    <input required id="number_scrapost" type="number" class="form-control" value="10">
                </div>
                <div class="mb-3">
                    <label for="postype_scrapost" class="form-label">Post Type</label>

                    <select id="postype_scrapost" class="form-select" aria-label="Select post type">
                        <option selected>Select Post Type</option>
                        <?php foreach ($postTypesList as $key => $cpt) {
                            echo '<option value="'.$cpt.'">'.$cpt.'</option>';
                        } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category_scrapost" class="form-label">Category</label>
                    <select required id="category_scrapost" class="form-select" aria-label="Select taxonomy">
                        <option selected>Select Post Type</option>
                        <?php foreach ($taxList as $key => $tax) {
                            echo '<option value="'.$tax.'">'.$tax.'</option>';
                        } ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Send</button>
            </form>
        </div>
        
    </div>
</div>