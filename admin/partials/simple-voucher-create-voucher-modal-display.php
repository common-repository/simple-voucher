<?php

/**
 * Provide a model to verify order details and create voucher
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
<!-- The Modal -->
<div class="simple_voucher_modal" id="simple_voucher-create-voucher">
    <div class="simple_voucher_modal-dialog">
        <div class="simple_voucher_modal-content">   
            <div id="simple_voucher_modal_loading" class="show">loading...</div>
            <div class="simple_voucher_modal-content-heading">
                <h5 class="simple_voucher_modal-content-heading-title" id="simple_voucher_modal_title">Simple Voucher</h5>     
                <span>Επαλήθευση καί δημιουργία του Voucher</span>
            </div>

            <!-- Modal body -->
            <div class="simple_voucher_modal-content-body">
                <div class="simple_voucher_row simple_voucher__form-group">
                    <label for="simple_voucher_order_notes" class="simple_voucher__form-label">Τύπος Voucher</label>
                    <select id="simple_voucher_voucher_type">
                    </select>
                </div>

                <div class="simple_voucher_row simple_voucher__form-group">
                    <label for="simple_voucher_cod" class="simple_voucher__form-label">Αποστολή με αντικαταβολή ?</label>
                    <div>
                        <input type="checkbox" class="simple_voucher_form-control" id="simple_voucher_cod"> <label for="simple_voucher_cod"><span id="simple_voucher_cod_wrapper">Σύνολο <span id="cod_amount">100</span> ευρώ</span></label>
                    </div>
                </div>

                <div class="simple_voucher_row simple_voucher__form-group" id="service_areas_service_areas_wrapper" style="display:none">
                    <label for="service_areas" class="simple_voucher__form-label" >Περιοχή εξυπηρέτησης</label>
                    <div>
                        <select id="service_areas">
                            <option class="default">Επιλέξτε περιοχή εξυπηρέτησης</option>
                        </select>
                    </div>
                </div>

                <div class="simple_voucher_row simple_voucher__form-group" id="additional_charges_wrapper">
                    <label class="simple_voucher__form-label" >Επιπλέον πεδία</label>
                    <div id="additional_charges_container"><div><input type="checkbox" class="additional_charges_checkbox" name="pdl01" id="additional_charges_checkbox_pdl01"><label for="additional_charges_checkbox_pdl01">ΠΑΡΑΔΟΣΗ-ΠΑΡΑΛΑΒΗ</label></div><div><input type="checkbox" class="additional_charges_checkbox" name="pdl02" id="additional_charges_checkbox_pdl02"><label for="additional_charges_checkbox_pdl02">ΠΑΡΑΔΟΣΗ ΣΑΒBΑΤΟΥ</label></div><div><input type="checkbox" class="additional_charges_checkbox" name="pdl03" id="additional_charges_checkbox_pdl03"><label for="additional_charges_checkbox_pdl03">ΠΑΡΑΔΟΣΗ RECEPTION</label></div></div>
                </div>

                <div class="simple_voucher_row simple_voucher__form-group" id="parcels_wrapper">
                    <label class="simple_voucher__form-label" >Τεμάχια</label>
                    <div>
                        <input type="number" value="1" min="1" class="simple_voucher_form-control" id="simple_voucher_parcels"/>
                    </div>
                </div>
                    
                <div class="simple_voucher_row simple_voucher__form-group">
                    <label for="simple_voucher_order_notes" class="simple_voucher__form-label">Σημειώσεις παραγγελίας</label>
                    <textarea class="simple_voucher_form-control" id="simple_voucher_order_notes" rows="5" ></textarea>
                </div>
                
                <div class="simple_voucher_row simple_voucher__form-group" >
                    <label class="simple_voucher__form-label">Υπάρχων Voucher παραγγελίας: <strong id="simple_voucher_voucher_id"></strong></label>
                    <div id="simple_voucher_order_voucher_link_wrapper" class="alert alert-success" style="display:none;">
                        <a href="#" id="simple_voucher_order_voucher_link" title="" target="_blank">Εκτύπωση υπάρχων Voucher</a>
                    </div>
                </div>
                

            </div>
            
            <!-- Modal footer -->
            <div class="simple_voucher_modal-content-footer">
                <button type="button" class="simple_voucher_btn simple_voucher__close-modal" >Ακύρωση</button>          
                <button type="button" class="simple_voucher_btn simple_voucher_btn-primary" id="simple_voucher__create_voucher"  style="margin-left:auto">Δημιουργία Voucher</button>          
                <button type="button" class="simple_voucher_btn simple_voucher_btn-secondary" id="simple_voucher__print_voucher" style="margin-left:auto; display:none;">Εκτύπωση υπάρχων Voucher</button>                  
                <button type="button" class="simple_voucher_btn" id="simple_voucher_btn_message" style="margin-left:auto" disabled>Επιλέξτε περιοχή εξυπηρέτησης</button>        
            </div>
        
        </div>
    </div>
</div>



