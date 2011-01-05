<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_data"><?php echo $tab_data; ?></a><a tab="#tab_links"><?php echo $tab_links; ?></a><a tab="#tab_discount"><?php echo $tab_discount; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a></div>
    <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data"/ id="form">
      <div id="tab_general">
        <div id="languages" class="htabs">
          <?php foreach ($languages as $language) { ?>
          <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		  <?php } ?>
        </div> 
        <?php foreach ($languages as $language) { ?>
        <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
                <?php if (isset($error_name[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_short_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][short_description]" cols="70" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['short_description'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_standard_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][standard_description]" id="standard_description<?php echo $language['language_id']; ?>" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['standard_description'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_keywords; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_description; ?></td>
              <td><textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
            </tr>
          </table>
        </div>
		<?php } ?>
      </div>
      <div id="tab_data">
        <table class="form">
        <tr>
            <td><?php echo $entry_model; ?></td>
            <td><input type="text" name="model" value="<?php echo $model; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_price; ?></td>
            <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_image; ?><br/><?=$entry_image_size?></td>
            <td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_upload_file_data; ?></td>
            <td>
            	<?if(isset($product_description[$language['language_id']]) && $product_description[$language['language_id']]['file_data']):?>
            		<?php echo $product_description[$language['language_id']]['file_data']; ?>&nbsp;<input type="checkbox" value="1" name="product_description[<?php echo $language['language_id']; ?>][remove_file_data]" /> Xóa<br/>
            	<?endif;?>
            	<input type="file" name="upload_file_data<?php echo $language['language_id']; ?>" size="30" />
            	<input type="hidden" name="product_description[<?php echo $language['language_id']; ?>][file_data]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['file_data'] : ''; ?>" />
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_upload_file_video; ?></td>
            <td>
            	<?if(isset($product_description[$language['language_id']]) && $product_description[$language['language_id']]['file_video']):?>
            		<?php echo  $product_description[$language['language_id']]['file_video']; ?>&nbsp;<input type="checkbox" value="1" name="product_description[<?php echo $language['language_id']; ?>][remove_file_video]" />Xóa<br/>
            	<?endif;?>
            	<input type="file" name="upload_file_video<?php echo $language['language_id']; ?>" size="30" />
            	<input type="hidden" name="product_description[<?php echo $language['language_id']; ?>][file_video]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['file_video'] : ''; ?>"/>
            	<?php if (isset($error_file_video[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_file_video[$language['language_id']]; ?></span>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_date_available; ?></td>
            <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
          </tr>
          <tr>
            <td><?php echo $chk_is_new_product; ?></td>
            <td><input type="checkbox" name="is_new_product" value="1" <?php echo ($is_new_product==1)?'checked="checked"':''; ?> /></td>
          </tr>
        </table>
      </div>
      <div id="tab_links">
        <table class="form">
        <tr>
            <td><?php echo $entry_manufacturer; ?></td>
            <td><select name="manufacturer_id">
                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                <?php foreach ($manufacturers as $manufacturer) { ?>
                <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_category; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($categories as $category) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($category['category_id'], $product_category)) { ?>
                  <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                  <?php echo $category['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                  <?php echo $category['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div></td>
          </tr>
          <!--<tr>
            <td><?/*php echo $entry_store; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array(0, $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                  <?php echo $text_default; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="0" />
                  <?php echo $text_default; ?>
                  <?php } ?>
                </div>
                <?php foreach ($stores as $store) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($store['store_id'], $product_store)) { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                  <?php echo $store['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                  <?php echo $store['name']; ?>
                  <?php } ?>
                </div>
                <?php } */?>
              </div></td>
          </tr>-->
        </table>
        <input type="hidden" name="product_store[]" value="0"  />
      </div>
      <div id="tab_discount">
        <table id="discount" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_priority; ?></td>
              <td class="left"><?php echo $entry_price; ?></td>
              <td class="left"><?php echo $entry_date_start; ?></td>
              <td class="left"><?php echo $entry_date_end; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $discount_row = 0; ?>
          <?php foreach ($product_discounts as $product_discount) { ?>
          <tbody id="discount_row<?php echo $discount_row; ?>">
            <tr>
              <td class="left">
              		<input type="hidden" name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" value="0" size="2" />
              		<input type="hidden" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="0" size="2" />
              		<input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" />
              </td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="date" /></td>
              <td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="date" /></td>
              <td class="left"><a onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $discount_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="left"><a onclick="addDiscount();" class="button"><span><?php echo $button_add_discount; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div id="tab_image">
        <table id="images" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_image; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $image_row = 0; ?>
          <?php foreach ($product_images as $product_image) { ?>
          <tbody id="image_row<?php echo $image_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="product_image[<?php echo $image_row; ?>]" value="<?php echo $product_image['file']; ?>" id="image<?php echo $image_row; ?>"  />
                <img src="<?php echo $product_image['preview']; ?>" alt="" id="preview<?php echo $image_row; ?>" class="image" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" /></td>
              <td class="left"><a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td></td>
              <td class="left"><a onclick="addImage();" class="button"><span><?php echo $button_add_image; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

CKEDITOR.replace('standard_description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script>
<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount_row' + discount_row + '">';
	html += '<tr>'; 
    html += '<td class="left"><input type="hidden" name="product_discount[' + discount_row + '][customer_group_id]" value="" size="2" /><input type="hidden" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
    html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
	html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
	html += '<td class="left"><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';	
    html += '</tbody>';
	
	$('#discount tfoot').before(html);
		
	$('#discount_row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	discount_row++;
}
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image_row' + image_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /> <?=$entry_image_size?></td>';
	html += '<td class="left"><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$.tabs('#tabs a'); 
$.tabs('#languages a'); 
//--></script>
<?php echo $footer; ?>