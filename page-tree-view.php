<?php
/* Template Name: Page - Tree View */ 


function tree_view_page_enqueue_multiple() {
	wp_enqueue_script('tree_view_page_js', get_stylesheet_directory_uri() .'/js/tree-view.js', array(), false, false);

    wp_enqueue_style ( 'tree-view-style', get_stylesheet_directory_uri() . '/css/tree-view-style.css' );

}
add_action( 'wp_enqueue_scripts', 'tree_view_page_enqueue_multiple' );

get_header();

if ( !is_user_logged_in()) 
{
    ?>
<div style="width:33px ;margin: auto;"><?php echo do_shortcode( '[TheChamp-Login show_username="ON"]' ); ?></div>
<?php
    // exit;
}else{
    global $current_user;
    $current_user = wp_get_current_user();
    $login_user_id = $current_user->ID;

    $the_action = $work_order_id = $work_order_number = "";
    $the_action = sanitize_text_field($_GET['action']);
    $work_order_id = sanitize_text_field($_GET['woid']);
    $work_order_number = sanitize_text_field($_GET['wonum']);

if($work_order_number == ""){
?>




<div id="loading-modal" class="w3-modal">
    <div id="loading-modal-content" class="w3-modal-content">
        <div class="w3-container">

            <div class="loading-boxes">
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <h3>I'm Loading...</h3>

        </div>
    </div>
</div>


<!-- Tree View Div -->
<h2 style="text-align: center;">SA25 Assembly Flowchart - Sheet 1</h2>
<h5 style="text-align: center;">Date: 11 SEP 2019 | Revision: M | Approved: Draf</h5>
</br>
<div class="tf-tree">
    <ul>
        <li>
            <span class="tf-nc">
                <h6>Pre-option Machine Assembly, SA25</h6>
            </span>
            <ul>
                <li>
                    <span class="tf-nc">

                        <h6>VPHP Port Cap 190 RTP Assembly</h6>
                        <p class="assembly_id">501312</p>

                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>RCS 190 RTP Assemply</h6>
                        <p class="assembly_id">501137</p>
                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>Robot Alignment Jit Assembly</h6>
                        <p class="assembly_id">501315</p>
                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>Stoppering Chamber to Accumlator Blanking Plates Assembly</h6>
                        <p class="assembly_id">501302</p>
                    </span>
                </li>

                <li>
                    <span class="tf-nc main-tf-cell">
                        <h6>Basic Electromechanical Assembly, SA25</h6>
                        <p>xxxx</p>
                    </span>
                    <ul>
                        <li>
                            <span class="tf-nc main-tf-cell work-done">
                                <h6>Stoppering Chamber Assembly</h6>
                                <p class="assembly_id">501276</p>
                            </span>
                            <ul>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Stoppering Chamber I/O Panel Assembly</h6>
                                        <p class="assembly_id">501164</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Stoppering Chamber Base Assembly</h6>
                                        <p class="assembly_id">501159</p>
                                    </span>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="tf-nc work-done">
                                <h6>Stoppering Chamber Wiring</h6>
                                <p class="assembly_id">501166</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc">
                                <h6>Main Electrical Panel Wiring</h6>
                                <p class="assembly_id">501353</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc">
                                <h6>17" HMI Assembly</h6>
                                <p class="assembly_id">501322</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc main-tf-cell">
                                <h6>Isolators Extrernal Assembly, SA25</h6>
                                <p>XXXXX</p>
                            </span>
                            <ul>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Fill Isolator Front Door Assembly</h6>
                                        <p class="assembly_id">501251</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>DSI Windows Assembly</h6>
                                        <p class="assembly_id">501266</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Carousel Glass Door Assembly</h6>
                                        <p class="assembly_id">501263</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc main-tf-cell">
                                        <h6>Isolators Internal Assembly, SA25</h6>
                                        <p class="assembly_id">XXXXX</p>
                                    </span>
                                    <ul>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Tray Robot (With Pusher) Assembly</h6>
                                                <p class="assembly_id">501323</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Particle Counter Assembly</h6>
                                                <p class="assembly_id">501248</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Fill Pedestal Assembly, Gen2</h6>
                                                <p class="assembly_id">504023</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Stoppering Pedestal Assembly</h6>
                                                <p class="assembly_id">501280</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Film And Nest Assembly, Gen2, SA25</h6>
                                                <p class="assembly_id">021710</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc main-tf-cell">
                                                <h6>Isolators Base Assembly, SA25</h6>
                                                <p class="assembly_id">501248</p>
                                            </span>
                                            <ul>
                                                <li>
                                                    <span class="tf-nc main-tf-cell">
                                                        <h6>Base Frame Assembly</h6>
                                                        <p class="assembly_id">501147</p>
                                                    </span>
                                                    <ul>
                                                        <li>
                                                            <span class="tf-nc main-tf-cell">
                                                                <h6>Base Frame Junction Box Assembly</h6>
                                                                <p class="assembly_id">501158</p>
                                                            </span>
                                                            <ul>
                                                                <li>
                                                                    <span class="tf-nc">
                                                                        <h6>JB0815 DSI Isolator Junction Box Assembly
                                                                        </h6>
                                                                        <p class="assembly_id">501194</p>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Base Frame Panel Rack 2 Assembly, SA25</h6>
                                                                <p class="assembly_id">501134</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Base Frame Panel Detub Pneumatics Assembly</h6>
                                                                <p class="assembly_id">501153</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Base Frame Panel JB0805 Assembly</h6>
                                                                <p class="assembly_id">501136</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Base Frame Panel Rack3 Assembly</h6>
                                                                <p class="assembly_id">501157</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Bottom Wiring</h6>
                                                                <p class="assembly_id">501037</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fill Isolator Bottom Wiring</h6>
                                                                <p class="assembly_id">501032</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Ethernet Wiring</h6>
                                                                <p class="assembly_id">501029</p>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <span class="tf-nc main-tf-cell">
                                                        <h6>Fill Isolator Assembly, SA25</h6>
                                                        <p class="assembly_id">XXXXX</p>
                                                    </span>
                                                    <ul>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fan Assy. Magnetic Coupling</h6>
                                                                <p class="assembly_id">020145</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Evaporator Assy. Gen3</h6>
                                                                <p class="assembly_id">600314</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Tyvek, Closure Camera Assembly</h6>
                                                                <p class="assembly_id">501199</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <h6><span class="tf-nc">Fill Camera Assembly</h6>
                                                            <p class="assembly_id">501091</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Flip Door Assembly</h6>
                                                                <p class="assembly_id">501023</p>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <span class="tf-nc main-tf-cell">
                                                        <h6>Fill Isolator Top Frame Assembly</h6>
                                                        <p class="assembly_id">501038</p>
                                                    </span>
                                                    <ul>
                                                        <li>
                                                            <span class="tf-nc main-tf-cell">
                                                                <h6>Fill Isolator Top Panel JB0820 Assembly</h6>
                                                                <p class="assembly_id">501021</p>
                                                            </span>
                                                            <ul>
                                                                <li>
                                                                    <span class="tf-nc">
                                                                        <h6>JB0820 Fill Isolator Junction Box Assembly
                                                                        </h6>
                                                                        <p class="assembly_id">501187</p>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fill Isolator Top Panel Rack 7 Assembly</h6>
                                                                <p class="assembly_id">501021</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fill Isolator Top Panel Rack 8 Assembly</h6>
                                                                <p class="assembly_id">501039</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fill Isolator Top Panel Main Air Supply Assembly
                                                                </h6>
                                                                <p class="assembly_id">501024</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fill Isolator Top Wiring</h6>
                                                                <p class="assembly_id">501022</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>JB0830 LED Status Beacon Assembly, Gen2</h6>
                                                                <p class="assembly_id">505117</p>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <span class="tf-nc main-tf-cell">
                                                        <h6>DSI Assembly</h6>
                                                        <p class="assembly_id">501147</p>
                                                    </span>
                                                    <ul>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Fan Assy. Magnetic Coupling</h6>
                                                                <p class="assembly_id">020145</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Evaporator Assy</h6>
                                                                <p class="assembly_id">600314</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Inlet HVAC Assembly</h6>
                                                                <p class="assembly_id">501267</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Exhaust HVAC Assembly</h6>
                                                                <p class="assembly_id">501265</p>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <span class="tf-nc main-tf-cell">
                                                        <h6>DIS Top Frame Assembly</h6>
                                                        <p class="assembly_id">501147</p>
                                                    </span>
                                                    <ul>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Panel Rack 6 Assembly</h6>
                                                                <p class="assembly_id">501046</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>Syringe Pump Assembly, Gen 2</h6>
                                                                <p class="assembly_id">502096</p>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <span class="tf-nc">
                                                                <h6>DSI Top Wiring</h6>
                                                                <p class="assembly_id">501014</p>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <!-- +++++++++++++++++++xxxx+++++++++ -->
                                            </ul>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Tub Peeler Assembly</h6>
                                                <p class="assembly_id">501200</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Stoppering Flip Door Assembly</h6>
                                                <p class="assembly_id">501089</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Diffuser Assembly</h6>
                                                <p class="assembly_id">501274</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Carousel Assembly, SA25. Gen3</h6>
                                                <p class="assembly_id">21879</p>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="tf-nc">
                                                <h6>Detub Pedestal Assembly</h6>
                                                <p class="assembly_id">501282</p>
                                            </span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Fill Isolator Back Door Assembly</h6>
                                        <p class="assembly_id">501261</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>190 RTP Port Assembly</h6>
                                        <p class="assembly_id">507125</p>
                                    </span>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="tf-nc">
                                <h6>Pneumatic Assembly</h6>
                                <p class="assembly_id">501354</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc">
                                <h6>Exhaust Filter Assembly</h6>
                                <p class="assembly_id">501293</p>
                            </span>
                        </li>

                        <li>
                            <span class="tf-nc">
                                <h6>Exhaust Filter Housing Assembly</h6>
                                <p class="assembly_id">501135</p>
                            </span>
                        </li>

                        <li>
                            <span class="tf-nc">
                                <h6>HVAC Return Assembly</h6>
                                <p class="assembly_id">501268</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc">
                                <h6>Waste Basket Assembly</h6>
                                <p class="assembly_id">501301</p>
                            </span>
                        </li>
                        <li>
                            <span class="tf-nc main-tf-cell">
                                <h6>Inlet Plenum Assembly</h6>
                                <p class="assembly_id">501131</p>
                            </span>
                            <ul>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Top Inlet Blenum Assembly</h6>
                                        <p class="assembly_id">501117</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Bottom Inlet Blenum Assembly</h6>
                                        <p class="assembly_id">501125</p>
                                    </span>
                                </li>
                                <li>
                                    <span class="tf-nc">
                                        <h6>Inlet Blenum Wiring</h6>
                                        <p class="assembly_id">501055</p>
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>Upper Shrouding Assembly, SA25</h6>
                        <p class="assembly_id">501360</p>
                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>Lowe Shrouding Assembly</h6>
                        <p class="assembly_id">501415</p>
                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>SA25 Shipping Materials Gen2</h6>
                        <p class="assembly_id">507254</p>
                    </span>
                </li>
                <li>
                    <span class="tf-nc">
                        <h6>SA25 Software License Package</h6>
                        <p class="assembly_id">505492</p>
                    </span>
                </li>
            </ul>

        </li>
    </ul>
</div>
<!-- End Tree View Div -->




<script>
    var log = console.log;
    jQuery(document).ready(function () {
        // +++++++++++++++++++++
        jQuery('.assembly_id').each(function (i, obj) {
            // log(jQuery('.assembly_id')[i].innerText);
        });

    }); // document ready end
</script>

<?php
  }
}
get_footer();