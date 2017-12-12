<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }



Class WC_Excel_Admin_Option extends WC_Excel {

    function __construct() {
        $this->plug_header();
        $this->admin_options();
        
        if(isset($_POST)) {
            foreach($_POST as $req => $rev) {
                $this->update_all_options( $this->plugin['slug'] . $req, $rev );
            }
        }

        add_action( 'admin_menu', array( $this, 'wc_excel_admin_menu' ) );
    }

    function update_all_options( $key, $value ) {
        update_option($key, $value);
    }

    function get_admin_options($key) {
        if(!empty($_POST))  
            return $_POST[$key];
        else
            return get_option( $this->plugin['slug'] . $key );
    }

    function plug_header() {
        echo '<link rel="stylesheet" href="' . $this->plugin['url'] . '/assets/style.css" />';
        echo '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">';
        echo '<h2><img src="'.$this->plugin['url'] . '/assets/admin-icon.png"> ' . __('ตั้งค่า ' . $this->plugin['name'] . '<sup>by iOTech</sup>', 'wc_excel_slip') . '</h2>';
    }

    function getFileList($dir, $recurse=false)
    {
      $retval = array();
  
      // add trailing slash if missing
      if(substr($dir, -1) != "/") $dir .= "/";
  
      // open pointer to directory and read list of files
      $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
      while(false !== ($entry = $d->read())) {
        // skip hidden files
        if($entry[0] == ".") continue;
        if(is_dir("$dir$entry")) {
          $retval[] = array(
            "name" => "$entry",
            "type" => filetype("$dir$entry"),
            "size" => 0,
            "lastmod" => filemtime("$dir$entry")
          );
          if($recurse && is_readable("$dir$entry/")) {
            $retval = array_merge($retval, getFileList("$dir$entry/", true));
          }
        } elseif(is_readable("$dir$entry")) {
          $retval[] = array(
            "name" => "$entry",
            "type" => mime_content_type("$dir$entry"),
            "size" => filesize("$dir$entry"),
            "lastmod" => filemtime("$dir$entry")
          );
        }
      }
      $d->close();
  
      return $retval;
    }

    function init_form_fields() {

        ?>
        <form method="POST" role="form">
            <legend><?php echo __('ข้อมูลเกี่ยวกับองค์กร','wc_excel_slip');?></legend>
        
            <div class="form-group">
                <label for="company_name"><?php echo __('บริษัท','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_name' );?>" name="_company_name" id="company_name" class="form-control" placeholder="<?php echo __('ชื่อบริษัท (สำนักงานใหญ่)','wc_excel_slip');?>">
            </div>

            <div class="form-group">
                <label for="shop_name"><?php echo __('ชื่อร้านค้า','wc_excel_slip');?></label>
             
                <input type="text" value="<?php echo $this->get_admin_options( '_shop_name' );?>" name="_shop_name" id="shop_name" class="form-control file-upload">
            </div>
            
            <div class="form-group">
                <label for="company_address_1"><?php echo __('เลขที่ หมู่ ตำบล/แขวง','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_address_1' );?>" name="_company_address_1" id="company_address_1" class="form-control" placeholder="<?php echo __('เลขที่ หมู่ ตำบล/แขวง','wc_excel_slip');?>" required="required">
            </div>
            <div class="form-group">
                <label for="company_address_2"><?php echo __('อำเภอ/เขต','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_address_2' );?>" name="_company_address_2" id="company_address_2" class="form-control required" placeholder="<?php echo __('อำเภอ/เขต','wc_excel_slip');?>" required="required">
            </div>
            <div class="form-group">
                <label for="company_province"><?php echo __('จังหวัด','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_province' );?>" name="_company_province" id="company_province" class="form-control" placeholder="<?php echo __('จังหวัด','wc_excel_slip');?>" required="required">
            </div>
            <div class="form-group">
                <label for="company_postcode"><?php echo __('รหัสไปรษณีย์','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_postcode' );?>" min="10000" name="_company_postcode" id="company_postcode" class="form-control" placeholder="<?php echo __('รหัสไปรษณีย์','wc_excel_slip');?>" required="required">
            </div>
            <div class="form-group">
                <label for="company_vatcode"><?php echo __('หมายเลขผู้เสียภาษี (ถ้ามี)','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_vatcode' );?>" name="_company_vatcode" id="company_vatcode" class="form-control" placeholder="<?php echo __('เลขที่ผู้เสียภาษี','wc_excel_slip');?>">
            </div>
            <div class="form-group">
                <label for="company_tel"><?php echo __('เบอร์โทรศัพท์ (ถ้ามี)','wc_excel_slip');?></label>
                <input type="text" value="<?php echo $this->get_admin_options( '_company_tel' );?>" name="_company_tel" id="company_tel" class="form-control" placeholder="<?php echo __('เบอร์โทร','wc_excel_slip');?>">
            </div>
            <br>
            <legend><?php echo __('ตั้งค่าอื่นๆ','wc_excel_slip');?></legend>
            <?php $template = $this->getFileList($this->plugin['dir'] . 'templates/');?>
            <div class="form-group">0
                <label for="template"><?php echo __('รูปแบบ','wc_excel_slip');?> <span class="current_setting"><?php echo $this->get_admin_options( '_template' );?></span></label>
                <select name="_template" id="template" class="form-control">
                    <?php for($x=0;$x<count($template);$x++): ?>
                        <option value="<?php echo $template[$x]['name'];?>"><?php echo $template[$x]['name'];?></option>
                    <?php endfor; ?>
                </select>
                
            </div>
        
            <?php submit_button(); ?>
        </form>
        
           
            
        <?php

    }

    function admin_options() {
        
         ?>
             <div class="options_area">
                <?php $this->init_form_fields(); ?>
                <hr>
                <p>Plugin created by <a href="https://iotech.co.th">iOTech</a></p>
            </div>
        <?php
    }

    function wc_excel_admin_menu(){
        
        $page_title = 'WooCommerce Excel Slip - Settings';
        $menu_title = 'WC Excel Slip';
        $capability = 'manage_options';
        $menu_slug  = 'wc_excel_setting';
        $function   = 'wc_excel_admin_page';
        $icon_url   = $this->plugin['url'] . '/assets/admin-icon.png';
        $position   = 30;
    
        add_menu_page( $page_title,
                        $menu_title, 
                        $capability, 
                        $menu_slug, 
                        $function, 
                        $icon_url, 
                        $position );
    }
}

function wc_excel_admin_page() {
    settings_fields('plugin_options');
    do_settings_sections('plugin');
    new WC_Excel_Admin_Option;
}