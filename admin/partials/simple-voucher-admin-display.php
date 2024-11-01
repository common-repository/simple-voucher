<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.danielsusanu.com
 * @since      1.0.0
 *
 * @package    Simple_Voucher
 * @subpackage Simple_Voucher/admin/partials
 */
?>
 

<div class="simple_voucher-page" >
    <!-- As a heading -->
    <nav class="simple_voucher-page__header">
        <div class="simple_voucher-page__header-wrapper">
            <h1 class="simple_voucher-page__header-heading">Simple Voucher Ρυθμίσεις</h1>
        </div>
    </nav>

    <!-- Body -->
    <div class="simple_voucher-page__layout">
        <div class="simple_voucher-page__container">

            <div class="simple_voucher-page__card">
            <div id="simple_voucher_modal_loading" class="show">loading...</div>
                <div class="simple_voucher-page__card__heading">
                    <div>
                        <p class="simple_voucher-page__card__heading-title">
                            Στοιχεία λογαριασμού
                        </p>
                        <p class="simple_voucher-page__card__heading-subtitle">
                            Συμπληρώστε τα στοιχεία που χρησιμοποιείται όταν κάνετε σύνδεση στο admin panel. 
                        </p>
                    </div>
                        
                </div>
                <div class="simple_voucher-page__card__body">
                    <form autocomplete="autocomplete_off_hack" method="post" action="options.php" id="simple_voucher_settings_form">
                        <?php 
                            settings_fields ('simple_voucher_settings');
                            do_settings_sections( 'simple_voucher_settings' );
                        ?>
                        <input type="hidden" name="simple_voucher_pdf_ids" value='[{"label":"Εκτύπωση σε A4(1/4)","id":1801}]' id="simple_voucher_pdf_ids" />                     
                        <input type="hidden" value="<?php 
                                                         $update_order_status = esc_attr(get_option( 'simple_voucher_update_order_status' ));
                                                         if($update_order_status == ''){
                                                             $update_order_status='false';
                                                         }     
                                                         echo $update_order_status; 
                                                         ?>" name="simple_voucher_update_order_status" />
                        <input type="hidden" value="" id="authResult" />                     
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_api_domain_id' )) ?>" name="simple_voucher_api_domain_id" id="simple_voucher_api_domain_id" />   
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_courier_name' )) ?>" name="simple_voucher_courier_name" id="simple_voucher_courier_name" />
                        <input type="hidden" value="<?php 
                                                        $showVoucherPdf = esc_attr(get_option( 'simple_voucher_show_voucher_pdf' ));
                                                        if($showVoucherPdf == ''){
                                                            $showVoucherPdf='true';
                                                        }       
                                                        echo $showVoucherPdf ?>" name="simple_voucher_show_voucher_pdf" id="simple_voucher_show_voucher_pdf" />  
                        <input type="hidden" value="<?php 
                                                        $requireServiceArea = esc_attr(get_option( 'simple_voucher_courier_require_service_area' ));
                                                        if($requireServiceArea == ''){
                                                            $requireServiceArea='true';
                                                        }       
                                                        echo $requireServiceArea; ?>" name="simple_voucher_courier_require_service_area" id="simple_voucher_courier_require_service_area" />
                        
                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_username" class="simple_voucher__col-2 simple_voucher__form-label">Domain</label>
                            <div class="simple_voucher__col-5">
                                <input type="text" class="simple_voucher_form-control" value="<?php 
                                   $apiDomain = get_option( 'simple_voucher_api_domain' );
                                   if($apiDomain != ''){
                                     echo esc_attr($apiDomain);
                                   }else{
                                     echo esc_attr('');
                                   }
                                ?>" 
                                name="simple_voucher_api_domain" id="simple_voucher_api_domain" autocomplete="autocomplete_off_hack" required>
                            </div>
                        </div>

                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_username" class="simple_voucher__col-2 simple_voucher__form-label">Όνομα Χρήστη</label>
                            <div class="simple_voucher__col-5">
                                <input type="text" class="simple_voucher_form-control" value="<?php echo esc_attr(get_option( 'simple_voucher_username' )) ?>" 
                                name="simple_voucher_username" id="simple_voucher_username" autocomplete="autocomplete_off_hack" required>
                            </div>
                        </div>
                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_route_code" class="simple_voucher__col-2 simple_voucher__form-label">Route Code: <small>προαιρετικό</small> </label>
                            <div class="simple_voucher__col-5">
                                <input type="text" class="simple_voucher_form-control" value="<?php echo esc_attr(get_option( 'simple_voucher_route_code' )) ?>" 
                                name="simple_voucher_route_code" id="simple_voucher_route_code" autocomplete="autocomplete_off_hack">
                            </div>
                        </div>
                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_password" class="simple_voucher__col-2 simple_voucher__form-label">Κωδικός</label>
                            <div class="simple_voucher__col-5">
                                <input type="password" class="simple_voucher_form-control" value="<?php echo esc_attr(get_option( 'simple_voucher_password' )) ?>" 
                                name="simple_voucher_password" id="simple_voucher_password" autocomplete="autocomplete_off_hack" required>
                                <?php 
                                $apiDomain = get_option( 'simple_voucher_api_domain' );
                                 if($apiDomain != ''){
                                     $simpleVoucherApiDomain = get_option( 'simple_voucher_api_domain' );
                                     $rememberPasswordUrl= 'https://' . $simpleVoucherApiDomain . '/el/Y%CF%80%CE%B5%CE%BD%CE%B8%CF%8D%CE%BC%CE%B9%CF%83%CE%B7%CE%9A%CF%89%CE%B4%CE%B9%CE%BA%CE%BF%CF%8D.htm';
                                    ?>
                                   
                                    <div style="margin-top:.5rem">
                                    <a href="<?php echo esc_url($rememberPasswordUrl) ?>" title="Ξέχασα το συνθηματικό μου" target="_blank">Ξέχασα το συνθηματικό μου!</a>
                                    </div>
                                    <?php 
                                 }
                                ?>
                                
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="simple_voucher_btn simple_voucher_btn-primary" id="simple_voucher_settings-submit-btn" >Αποθηκευση</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="simple_voucher-page__card">
                <div class="simple_voucher-page__card__heading">
                    <div>
                        <p class="simple_voucher-page__card__heading-title">
                            Ρυθμίσεις
                        </p>
                    </div>
                        
                </div>
                <div class="simple_voucher-page__card__body">
                    <form autocomplete="autocomplete_off_hack" method="post" action="options.php" >
                        <?php 
                            settings_fields ('simple_voucher_settings');
                            do_settings_sections( 'simple_voucher_settings' );
                        ?>       
                        <input type="hidden" name="simple_voucher_pdf_ids" value='[{"label":"Εκτύπωση σε A4(1/4)","id":1801}]' />                                
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_api_domain_id' )) ?>" name="simple_voucher_api_domain_id" />
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_api_domain' )) ?>" name="simple_voucher_api_domain" />
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_username' )) ?>" name="simple_voucher_username" />
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_password' )) ?>" name="simple_voucher_password" />     
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_route_code' )) ?>" name="simple_voucher_route_code" />     
                        <input type="hidden" value="<?php echo esc_attr(get_option( 'simple_voucher_courier_name' )) ?>" name="simple_voucher_courier_name" />                          
                        <input type="hidden" value="<?php 
                                                        $requireServiceArea = esc_attr(get_option( 'simple_voucher_courier_require_service_area' ));
                                                        if($requireServiceArea == ''){
                                                            $requireServiceArea='true';
                                                        }       
                                                        echo $requireServiceArea ?>" name="simple_voucher_courier_require_service_area" />
                                           

                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_update_order_status" class="simple_voucher__col-12 simple_voucher__form-label">Αλλαγή κατάστασης παραγγελίας</label>
                            <div class="simple_voucher__col-12">                                
                                <select name="simple_voucher_update_order_status" id="simple_voucher_update_order_status">
                                    <option value="false" <?php   
                                        $status = esc_attr(get_option( 'simple_voucher_update_order_status' ));
                                        if($status == "false" || $status='') echo 'selected="selected"';
                                    ?> >Όχι να μην αλλάξει</option>
                                    <option value="false" disabled>--------------------------</option>
                                    <option value="wc-completed" <?php   
                                        $status = esc_attr(get_option( 'simple_voucher_update_order_status' ));
                                        if($status == "wc-completed") echo 'selected="selected"';
                                    ?>>Ολοκληρωμένη</option>
                                </select>
                                <br>
                                <small>Αλλάζει την κατάσταση της παραγγελίας όταν η παραγγελία λαμβει voucher αποστολής</small>
                            </div>
                        </div>

                        <div class="simple_voucher_row simple_voucher__form-group">
                            <label for="simple_voucher_show_voucher_pdf" class="simple_voucher__col-12 simple_voucher__form-label">Άνοιγμα voucher σε νέα καρτέλα</label>
                            <div class="simple_voucher__col-12">                                
                                <select name="simple_voucher_show_voucher_pdf" id="simple_voucher_show_voucher_pdf">
                                    <option value="true" <?php   
                                        $status = esc_attr(get_option( 'simple_voucher_show_voucher_pdf' ));
                                        if($status == "true" || $status='') echo 'selected="selected"';
                                    ?> >Ναι να ανοίγει</option>
                                    <option value="false" <?php   
                                        $status = esc_attr(get_option( 'simple_voucher_show_voucher_pdf' ));
                                        if($status == "false") echo 'selected="selected"';
                                    ?>>Όχι να μην ανοίγει</option>
                                </select>
                            </div>
                        </div>
                                                
                        <div>
                            <button type="submit" class="simple_voucher_btn simple_voucher_btn-primary">Αποθηκευση</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>    
    </div> 
</div>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
