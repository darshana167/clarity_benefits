
<?php
//
// SSO TILES
define('SSO_COMPANY_CP_TPA', 19437);
define('SSO_COMPANY_CP_BROKER', 180);
define('SSO_COMPANY_CP_CLIENT', 178);
define('SSO_COMPANY_CP_TEMP_INACTIVE', 19847);
define('SSO_COMPANY_STILL_PROCESSING_SSO', 8347);
define('SSO_COMPANY_WCA_TPA', 8819);
define('SSO_COMPANY_WCA_BROKER', 8819);
define('SSO_COMPANY_WCA_CLIENT', 176);
define('SSO_COMPANY_WCA_ADMIN_TEMP_INACTIVE', 19399);

define('SSO_COMPANY_BS_TPA', 469);
define('SSO_COMPANY_BS_BROKER', 469);
define('SSO_COMPANY_BS_CLIENT', 469);
define('SSO_COMPANY_EN_TPA', 843);
define('SSO_COMPANY_EN_BROKER', 843);
define('SSO_COMPANY_EN_CLIENT', 843);
define('SSO_PART_CP', 479);
define('SSO_PART_CP_TEMP_INACTIVE', 19847);
define('SSO_PART_STILL_PROCESSING_SSO', 8347);
define('SSO_PART_WCP', 477);
define('SSO_PART_WCP_TEMP_INACTIVE', -1001);
define('SSO_PART_BS', 475);
define('SSO_PART_EN', 841);
define('SSO_NO_AUDIENCE', 11101);
//additional resource
define('SSO_ADDITIONAL_BILLTRUST', 19186);
define('SSO_ADDITIONAL_RTO', 19188);
define('SSO_ADDITIONAL_BENCONNECT', 19190);
define('SSO_ADDITIONAL_COMMUTEWISE', 19192);
//
$SSOBSwiftTiles = array(SSO_COMPANY_BS_TPA, SSO_COMPANY_BS_BROKER, SSO_COMPANY_BS_CLIENT, SSO_PART_BS);
$SSOCPAllowSSOTiles = array(SSO_COMPANY_CP_BROKER, SSO_COMPANY_CP_CLIENT, SSO_PART_CP);
$SSOENAllowSSOTiles = array(SSO_COMPANY_EN_BROKER, SSO_COMPANY_EN_CLIENT, SSO_PART_EN);



if (!class_exists('ClarityShortodes')) {

class ClarityShortodes
{

    public function __construct()
    {
        global $post;
    }

    public function clarity_shortcode()
    {
        add_shortcode('cl_get_user_sso_tiles', array($this, 'display_all_tyles_new'));
       /* displayin your benefits tiles shortcode */
        add_shortcode('cl_get_your_benefits', array($this, 'get_your_benefits'));
      
          /* displayin company benefits tiles shortcode */
        add_shortcode('cl_get_company_benefits', array($this, 'get_company_benefits'));
       
        $this->clarity_filter();
    }


         /**
         * @create for: need to display all tiles in frontend according to audiences
         * @created by: Darshana
         * @at: 15/7/2020
         * */
        public
        function display_all_tyles_new($attr)
        {
            $user_data = wp_get_current_user();
            if (isset($_SESSION['sso_data'])) {
                $ssodata = json_decode($_SESSION['sso_data']);
            }

            $listItem = '';
            $user_data = wp_get_current_user();
            $showFSAStoreTab = false;

            /** if user is login as a SSO user */
            if (!empty($ssodata)) {
                if ($ssodata->is_admin_user || $ssodata->is_particpant) {
                    $listItem .= '<div id="benefits_section"> <ul class="nav nav-tabs user_role_ssl_tab">';

                    //                     company tab
                    if (($ssodata->is_admin_user) == 1) {

                        $listItem .= '<li class="active"><a data-toggle="tab" href="#menu1">Manage Your Company Benefits</a></li>';
                    }
                    //                     part tab
                    if (($ssodata->is_particpant) == 1) {
                        $listItem .= '<li><a data-toggle="tab" href="#menu">Manage Your Benefits</a></li>';
                    }
                    if ($showFSAStoreTab == true && (($ssodata->is_tpa_admin) || ($ssodata->is_broker) || ($ssodata->is_client) || ($ssodata->is_particpant == 1))) {
                        $listItem .= '<li><a data-toggle="tab" href="#menu2">FSA, HSA and HRA Eligible Expenses</a></li>';
                    }
                    $listItem .= '</ul>';
                } else {
                    $listItem .= '';
                }

                //  the tabs themselves
                if ($ssodata->is_admin_user == 1 || $ssodata->is_particpant == 1) {
                    $listItem .= ' <div class="tab-content user_role_ssl_tab_contnt">';

                    //                     company benefits tab
                    if ($ssodata->is_admin_user == 1) {
                        $listItem .= '<h4 class="site_heading heading-tiles" id="site_heading" style="display:none;color: #224A8B;
                                font-size: 18px;
                                padding-left: 20px;
                                font-weight: bold;
                                letter-spacing: 0;
                                min-height: 50px;
                                line-height: 24px !important;
                                margin-bottom: 20px;">Manage Your Company Benefits</h4>';

                        $listItem .= '<div id="menu1" class="tab-pane user_role_ssl_tab_pane fade in active">';
                        $listItem .= '<p class="heading">Manage And Learn more about your company benefit plans.</p>';

                        $listItem .= do_shortcode('[cl_get_company_benefits post_per_page ="' . $attr['companybenefits'] . '"]');

                        if ((($ssodata->is_client) == 1 || ($ssodata->is_tpa_admin) == 1) && ($ssodata->is_broker) == 1) {
                            $get_user_role = "client,broker";
                        } elseif (($ssodata->is_broker) == 1) {
                            $get_user_role = "broker";
                        } else {
                            $get_user_role = "client";
                        }

                        //  $listItem .= do_shortcode('[cl_get_learn_more audience_types="' . $get_user_role . '"]');

                        $listItem .= '<div class="row communication_test" style="clear:both;"><div class="col-md-6"><h4 class="site_heading heading-tilesTest" id="site_heading">
                                      Communications</h4> ';

                        $listItem .= do_shortcode('[cl_get_communication_based_audience post_per_page="10"  audience_types="' . $get_user_role . '"]');
                        $listItem .= '</div><div class="col-md-6"><h4 class="site_heading heading-tilesTest" id="site_heading">Did You Know?</h4> ';
                        $listItem .= do_shortcode('[cl_get_testimonial_based_audience post_per_page="5" audience_types="' . $get_user_role . '"]');

                        $listItem .= '</div></div>';
                        $listItem .= '</div>';
                    }
                    //                     participant tab
                    if ($ssodata->is_particpant == 1) {
                        $listItem .= '<h4 class="site_heading heading-tiles" id="site_heading"  style="display:none;color: #224A8B;
                            font-size: 18px;
                            padding-left: 20px;
                            font-weight: bold;
                            letter-spacing: 0;
                            min-height: 50px;
                            line-height: 24px !important;
                            margin-bottom: 20px;">Manage Your Benefits</h4>';

                        $listItem .= '<div id="menu" class="tab-pane user_role_ssl_tab_pane fade"><p class="heading">Manage And Learn more about your current benefit plans.</p>';


                        $listItem .= do_shortcode('[cl_get_your_benefits post_per_page ="' . $attr['yourbenefits'] . '" ]');
                        //   $listItem .= do_shortcode('[cl_get_learn_more audience_types="member"]');
                        $listItem .= '<div class="row communication_test" style="clear:both;"><div class="col-md-6"><h4 class="site_heading heading-tilesTest" id="site_heading">
                                      Communications</h4> ';
                        $listItem .= do_shortcode('[cl_get_communication_based_audience post_per_page="10" audience_types="member"]');
                        $listItem .= '</div><div class="col-md-6"><h4 class="site_heading heading-tilesTest" id="site_heading">Did You Know?</h4> ';
                        $listItem .= do_shortcode('[cl_get_testimonial_based_audience post_per_page="5" audience_types="member"]');
                        $listItem .= '</div></div>';
                        $listItem .= '</div>';
                    }
                    // commented by darshana need to uncomment after adding third tab
                    //  $listItem .= '</div></div>';
                } else {
                    /* if user role is not set user logs in, has record and is_verified=1 but has no audience type	 */
                    $listItem .= do_shortcode('[cl_no_audience_tile_display ]');
                    //  $listItem .= do_shortcode('[cl_get_learn_more audience_types="guest"]');
                    $listItem .= '<div class="row communication_test" style="clear:both;"><div class="col-md-6"><h4 class="site_heading" id="site_heading"><span>Communications</span></h4> ';
                    $listItem .= do_shortcode('[cl_get_communication_based_audience post_per_page="10" audience_types="guest"]');
                    $listItem .= '</div><div class="col-md-6"><h4 class="site_heading" id="site_heading"><span>Did You Know?</span></h4> ';
                    $listItem .= do_shortcode('[cl_get_testimonial_based_audience post_per_page="5" audience_types="guest"]');
                    $listItem .= '</div>';
                }
//                 added by sumeet to fix fsa tab appreaing at bottom

                if ($showFSAStoreTab == true && (($ssodata->is_tpa_admin) || ($ssodata->is_broker) || ($ssodata->is_client) || ($ssodata->is_particpant))) {
                    $listItem .= '<div id="menu2" class="tab-pane user_role_ssl_tab_pane fade">';
                    $listItem .= do_shortcode('[cl_fsa_store_header_shortcode]');
                    $listItem .= do_shortcode('[cl_fsa_store_shortcode]');
                    $listItem .= '</div>';
                }

                $listItem .= '</div></div>';
            }
            return $listItem;
        }


public
        function get_your_benefits($attr)
        {
            if (isset($_SESSION['sso_data'])) {
                $ssodata = json_decode($_SESSION['sso_data']);
                $ssodataD = (array)$ssodata;
                $get_key_arrayD = array_keys($ssodataD);
            }

            $ssoTiles = array();

            if ($ssodata->is_ready_for_sso_processing != 1) {
                $ssoTiles[] = SSO_COMPANY_STILL_PROCESSING_SSO;
            } else {
                //             WC
                if ($ssodata->is_wc_particpant) {
                    if ($ssodata->wcp_user_is_active == 1) {
                        $ssoTiles[] = SSO_PART_WCP;
                    } elseif ($ssodata->wcp_user_is_active == 2) {
                        $ssoTiles[] = SSO_PART_WCP_TEMP_INACTIVE;
                    }
                }
                // CP
                if ($ssodata->is_cp_particpant) {
                    if ($ssodata->cp_member_user_is_active == 1) {
                        $ssoTiles[] = SSO_PART_CP;
                    } elseif ($ssodata->cp_member_user_is_active == 2) {
                        $ssoTiles[] = SSO_PART_CP_TEMP_INACTIVE;
                    }
                }

                // BS
                if ($ssodata->is_bs_particpant) {
                    $ssoTiles[] = SSO_PART_BS;
                }

                // EN
                if ($ssodata->is_en_particpant) {
                    $ssoTiles[] = SSO_PART_EN;
                }
                //

            }

            if (count($ssoTiles) == 0) {
                $ssoTiles[] = SSO_PART_STILL_PROCESSING_SSO;
            }

            if ($ssoTiles && count($ssoTiles) > 0) {

                $args = array('post_type' => 'sso_tiles', 'posts_per_page' => $attr['post_per_page'], 'post__in' => $ssoTiles);
                $listItem = "";

                $loop = new WP_Query($args);
                $total = $loop->found_posts;

                $listItem .= '<div class="row row-m0 mob-m-0">';

                //            if total ==0, show no sso tile
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) {
                        $loop->the_post();
                        $id = get_the_ID();
                        $get_sso_title = get_the_title();
                        $get_sso_content = get_the_content();
                        $platform_type = get_post_meta($id, 'platform_type_selector', true);
                        $manage_tool_tips = get_post_meta($id, 'manage_tool_tips', true);
                        $learn_more_tool_tips = get_post_meta($id, 'learn_more_tool_tips', true);

                        //  print_r($ssodata);
                        $platform_name = getPlatformType($platform_type);
                        if (($ssodata) && $ssodata->bs_abbrev_url) {
                            $platform_type_selector = get_post_meta($id, 'platform_type_selector', true);

                            if ($ssodata->bs_abbrev_url == '' || $ssodata->bs_abbrev_url == 'clarity') {
                                $sso_tiles_url = get_post_meta($id, 'bswift_sso_url1', true);
                            } else {
                                $sso_tiles_url = get_post_meta(tid, $ssodata->bs_abbrev_url, true);
                            }
                        } else {
                            $sso_tiles_url = get_post_meta($id, 'sso_url', true);
                        }


                        $sso_tiles_actual_url = '';
                        $sso_tiles_actual_headline = '';
                        $sso_tiles_actual_content = '';

                        global $SSOCPAllowSSOTiles;
                        global $SSOENAllowSSOTiles;

                        if (in_array($id, $SSOCPAllowSSOTiles)) {
                            if (($ssodata->is_cp_particpant) == 1 && ($ssodata->cp_allow_sso) == 0) {
                                $sso_tiles_actual_url = $sso_tiles_url;
                                $sso_tiles_url = "/redirect-to-cobra/";
                                $sso_tiles_actual_headline = get_the_title();
                                $sso_tiles_actual_content = get_the_content();
                                $get_sso_title = "CobraPoint is Processing Your Access";
                                $get_sso_content = "Your single sign on access to CobraPoint is processing. This can take up to 5 minutes. Once complete this tile will update and you will be able to link to the application directly.";

                            }
                        } elseif (in_array($id, $SSOENAllowSSOTiles)) {
                            if (($ssodata->is_en_particpant) == 1) {
                                //                             redirect to cobra site for login
                                $sso_tiles_url = "/redirect-to-enav/";
                            }
                        }

                        $add_color = get_post_meta($id, 'choose_color_for_sso_tiles', true);

                        $image = (has_post_thumbnail($id)) ? get_the_post_thumbnail($id, 'realty_widget_size') : '<div class="noThumb" style="height:50px;"></div>';
                        $widthCount = ($ssodata->is_en_particpant) == 1 ? 3 : 4;

                        if ($total >= $widthCount) {
                            $listItem .= '<div class="col-md-3 col-sm-6 manage_beneifts form-group" style="color: black;
                                          margin-right: 0px;margin-left:0px;">';
                        } else {
                            $listItem .= '<div class="col-md-4 col-sm-6 manage_beneifts form-group" style=" color: black;
                                        margin-right: 0px;margin-left:0px;">';
                        }

                        $listItem .= '<div class="benefitRow"><a href="' . $sso_tiles_url . '" target="_blank"><p class="platform_name_sso" style="display:none">' . $platform_name . '</p><div id="sso_image" style="height:auto">' . $image . '</div></a>';
                        $listItem .= '</br>';
                        $listItem .= '<a class="sso-url-' . $id . '" href="' . $sso_tiles_url . '" target="_blank"><h4 class="manage_ben_title sso-title-' . $id . '"">' . $get_sso_title . '</h4></a>';
                        $listItem .= '<a  class="sso-url-' . $id . '" href="""' . $sso_tiles_url . '" target="_blank"><span class="get_content sso-content-' . $id . '"">' . $get_sso_content . '</span></a>';
                        $listItem .= '<div class="get_plans clearfix">
                                    <a  class="sso-url-' . $id . '" href="' . $sso_tiles_url . '" target="_blank"><div class="get_plan_left"><span> Manage Plan <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptext">
                    ' . $manage_tool_tips . '</span>
 
                </div></a><div class="get_plan_right"><span>Learn More <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptextR">
                ' . $learn_more_tool_tips . '</span></div></div></div></a></div>';

                        /** check checkAllowSSo value for COBRA */
                        if ($sso_tiles_actual_url != '' && $sso_tiles_actual_url != $sso_tiles_url) {
                            // call js function checkAllowSSO(sso-url- . $id,   $sso_tiles_actual_url ) that will call getAllowSSO (user_id)
                            // setTimeout(3000, function () -> do-auth, getAllowSSO, if ==1  change url to $sso_tiles_actual_url)

                            checkAllowSSoValue($sso_tiles_actual_url, $id, $sso_tiles_actual_headline, $sso_tiles_actual_content);
                        }
                    }
                }
            } //count(ssotiles)

            $listItem .= '</div>';
            $listItem .= $this->getAdditionalTiles();
            return $listItem;
        }



           public
        function get_your_benefits($attr)
        {
            if (isset($_SESSION['sso_data'])) {
                $ssodata = json_decode($_SESSION['sso_data']);
                $ssodataD = (array)$ssodata;
                $get_key_arrayD = array_keys($ssodataD);
            }

            $ssoTiles = array();

            if ($ssodata->is_ready_for_sso_processing != 1) {
                $ssoTiles[] = SSO_COMPANY_STILL_PROCESSING_SSO;
            } else {
                //             WC
                if ($ssodata->is_wc_particpant) {
                    if ($ssodata->wcp_user_is_active == 1) {
                        $ssoTiles[] = SSO_PART_WCP;
                    } elseif ($ssodata->wcp_user_is_active == 2) {
                        $ssoTiles[] = SSO_PART_WCP_TEMP_INACTIVE;
                    }
                }
                // CP
                if ($ssodata->is_cp_particpant) {
                    if ($ssodata->cp_member_user_is_active == 1) {
                        $ssoTiles[] = SSO_PART_CP;
                    } elseif ($ssodata->cp_member_user_is_active == 2) {
                        $ssoTiles[] = SSO_PART_CP_TEMP_INACTIVE;
                    }
                }

                // BS
                if ($ssodata->is_bs_particpant) {
                    $ssoTiles[] = SSO_PART_BS;
                }

                // EN
                if ($ssodata->is_en_particpant) {
                    $ssoTiles[] = SSO_PART_EN;
                }
                //

            }

            if (count($ssoTiles) == 0) {
                $ssoTiles[] = SSO_PART_STILL_PROCESSING_SSO;
            }

            if ($ssoTiles && count($ssoTiles) > 0) {

                $args = array('post_type' => 'sso_tiles', 'posts_per_page' => $attr['post_per_page'], 'post__in' => $ssoTiles);
                $listItem = "";

                $loop = new WP_Query($args);
                $total = $loop->found_posts;

                $listItem .= '<div class="row row-m0 mob-m-0">';

                //            if total ==0, show no sso tile
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) {
                        $loop->the_post();
                        $id = get_the_ID();
                        $get_sso_title = get_the_title();
                        $get_sso_content = get_the_content();
                        $platform_type = get_post_meta($id, 'platform_type_selector', true);
                        $manage_tool_tips = get_post_meta($id, 'manage_tool_tips', true);
                        $learn_more_tool_tips = get_post_meta($id, 'learn_more_tool_tips', true);

                        //  print_r($ssodata);
                        $platform_name = getPlatformType($platform_type);
                        if (($ssodata) && $ssodata->bs_abbrev_url) {
                            $platform_type_selector = get_post_meta($id, 'platform_type_selector', true);

                            if ($ssodata->bs_abbrev_url == '' || $ssodata->bs_abbrev_url == 'clarity') {
                                $sso_tiles_url = get_post_meta($id, 'bswift_sso_url1', true);
                            } else {
                                $sso_tiles_url = get_post_meta(tid, $ssodata->bs_abbrev_url, true);
                            }
                        } else {
                            $sso_tiles_url = get_post_meta($id, 'sso_url', true);
                        }


                        $sso_tiles_actual_url = '';
                        $sso_tiles_actual_headline = '';
                        $sso_tiles_actual_content = '';

                        global $SSOCPAllowSSOTiles;
                        global $SSOENAllowSSOTiles;

                        if (in_array($id, $SSOCPAllowSSOTiles)) {
                            if (($ssodata->is_cp_particpant) == 1 && ($ssodata->cp_allow_sso) == 0) {
                                $sso_tiles_actual_url = $sso_tiles_url;
                                $sso_tiles_url = "/redirect-to-cobra/";
                                $sso_tiles_actual_headline = get_the_title();
                                $sso_tiles_actual_content = get_the_content();
                                $get_sso_title = "CobraPoint is Processing Your Access";
                                $get_sso_content = "Your single sign on access to CobraPoint is processing. This can take up to 5 minutes. Once complete this tile will update and you will be able to link to the application directly.";

                            }
                        } elseif (in_array($id, $SSOENAllowSSOTiles)) {
                            if (($ssodata->is_en_particpant) == 1) {
                                //                             redirect to cobra site for login
                                $sso_tiles_url = "/redirect-to-enav/";
                            }
                        }

                        $add_color = get_post_meta($id, 'choose_color_for_sso_tiles', true);

                        $image = (has_post_thumbnail($id)) ? get_the_post_thumbnail($id, 'realty_widget_size') : '<div class="noThumb" style="height:50px;"></div>';
                        $widthCount = ($ssodata->is_en_particpant) == 1 ? 3 : 4;

                        if ($total >= $widthCount) {
                            $listItem .= '<div class="col-md-3 col-sm-6 manage_beneifts form-group" style="color: black;
                                          margin-right: 0px;margin-left:0px;">';
                        } else {
                            $listItem .= '<div class="col-md-4 col-sm-6 manage_beneifts form-group" style=" color: black;
                                        margin-right: 0px;margin-left:0px;">';
                        }

                        $listItem .= '<div class="benefitRow"><a href="' . $sso_tiles_url . '" target="_blank"><p class="platform_name_sso" style="display:none">' . $platform_name . '</p><div id="sso_image" style="height:auto">' . $image . '</div></a>';
                        $listItem .= '</br>';
                        $listItem .= '<a class="sso-url-' . $id . '" href="' . $sso_tiles_url . '" target="_blank"><h4 class="manage_ben_title sso-title-' . $id . '"">' . $get_sso_title . '</h4></a>';
                        $listItem .= '<a  class="sso-url-' . $id . '" href="""' . $sso_tiles_url . '" target="_blank"><span class="get_content sso-content-' . $id . '"">' . $get_sso_content . '</span></a>';
                        $listItem .= '<div class="get_plans clearfix">
                                    <a  class="sso-url-' . $id . '" href="' . $sso_tiles_url . '" target="_blank"><div class="get_plan_left"><span> Manage Plan <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptext">
                    ' . $manage_tool_tips . '</span>
 
                </div></a><div class="get_plan_right"><span>Learn More <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptextR">
                ' . $learn_more_tool_tips . '</span></div></div></div></a></div>';

                        /** check checkAllowSSo value for COBRA */
                        if ($sso_tiles_actual_url != '' && $sso_tiles_actual_url != $sso_tiles_url) {
                            // call js function checkAllowSSO(sso-url- . $id,   $sso_tiles_actual_url ) that will call getAllowSSO (user_id)
                            // setTimeout(3000, function () -> do-auth, getAllowSSO, if ==1  change url to $sso_tiles_actual_url)

                            checkAllowSSoValue($sso_tiles_actual_url, $id, $sso_tiles_actual_headline, $sso_tiles_actual_content);
                        }
                    }
                }
            } //count(ssotiles)

            $listItem .= '</div>';
            $listItem .= $this->getAdditionalTiles();
            return $listItem;
        }




        public
        function get_company_benefits($attr)
        {
            if (isset($_SESSION['sso_data'])) {
                $ssodata = json_decode($_SESSION['sso_data']);
                $ssodataD = (array)$ssodata;
                $get_key_arrayD = array_keys($ssodataD);
            }

            $ssoTiles = array();

            if ($ssodata->is_ready_for_sso_processing != 1) {
                $ssoTiles[] = SSO_COMPANY_STILL_PROCESSING_SSO;
            } else {
                //             WC
                if ($ssodata->is_wc_tpa_admin) {
                    if ($ssodata->wca_user_is_active == 1) {
                        $ssoTiles[] = SSO_COMPANY_WCA_TPA;
                    } elseif ($ssodata->wca_user_is_active == 2) {
                        $ssoTiles[] = SSO_COMPANY_WCA_ADMIN_TEMP_INACTIVE;
                    }
                } elseif ($ssodata->is_wc_broker) {
                    if ($ssodata->wca_user_is_active == 1) {
                        $ssoTiles[] = SSO_COMPANY_WCA_BROKER;
                    } elseif ($ssodata->wca_user_is_active == 2) {
                        $ssoTiles[] = SSO_COMPANY_WCA_ADMIN_TEMP_INACTIVE;
                    }

                } elseif ($ssodata->is_wc_client) {
                    if ($ssodata->wca_user_is_active == 1) {
                        $ssoTiles[] = SSO_COMPANY_WCA_CLIENT;
                    } elseif ($ssodata->wca_user_is_active == 2) {
                        $ssoTiles[] = SSO_COMPANY_WCA_ADMIN_TEMP_INACTIVE;
                    }
                }
                // CP
                if ($ssodata->is_cp_tpa_admin) {
                    $ssoTiles[] = SSO_COMPANY_CP_TPA;
                } elseif ($ssodata->is_cp_broker) {
                    if ($ssodata->cp_broker_user_is_active == 1) {
                        $ssoTiles[] = SSO_COMPANY_CP_BROKER;
                    } elseif ($ssodata->cp_broker_user_is_active == 2) {
                        $ssoTiles[] = SSO_COMPANY_CP_TEMP_INACTIVE;
                    }
                } elseif ($ssodata->is_cp_client) {
                    if ($ssodata->cp_client_user_is_active == 1) {
                        $ssoTiles[] = SSO_COMPANY_CP_CLIENT;
                    } elseif ($ssodata->cp_client_user_is_active == 2) {
                        $ssoTiles[] = SSO_COMPANY_CP_TEMP_INACTIVE;
                    }
                }

                // BS
                if ($ssodata->is_bs_tpa_admin) {
                    $ssoTiles[] = SSO_COMPANY_BS_TPA;
                } elseif ($ssodata->is_bs_broker) {
                    $ssoTiles[] = SSO_COMPANY_BS_BROKER;
                } elseif ($ssodata->is_bs_client) {
                    $ssoTiles[] = SSO_COMPANY_BS_CLIENT;
                }

                // EN
                if ($ssodata->is_en_tpa_admin) {
                    $ssoTiles[] = SSO_COMPANY_EN_TPA;
                } elseif ($ssodata->is_en_broker) {
                    $ssoTiles[] = SSO_COMPANY_EN_BROKER;
                } elseif ($ssodata->is_en_client || $ssodata->is_en_particpant) {
                    $ssoTiles[] = SSO_COMPANY_EN_CLIENT;
                }
                //

            }
            if (count($ssoTiles) == 0) {
                $ssoTiles[] = SSO_COMPANY_STILL_PROCESSING_SSO;
            }
            if ($ssoTiles && count($ssoTiles) > 0) {
                $argsData = array('post_type' => 'sso_tiles', 'post__in' => $ssoTiles);

                $companyPost = new WP_Query($argsData);
                $noOfTiles = $companyPost->found_posts;

                $listItem = '<div class="row row-m0 mob-m-0">';
                wp_reset_postdata();


                if ($companyPost->have_posts()) {

                    //                 todo: get number of tiles here
                    while ($companyPost->have_posts()) {
                        $companyPost->the_post();
                        $getid = get_the_ID();

                        $get_sso_title = get_the_title();
                        $get_sso_content = get_the_content();
                        //
                        $platform_name = $getid;

                        //                    $platform_type = get_post_meta($getid, 'platform_type_selector', true);
                        $manage_tool_tips = get_post_meta($getid, 'manage_tool_tips', true);
                        $learn_more_tool_tips = get_post_meta($getid, 'learn_more_tool_tips', true);

                        //                    $platform_name = getPlatformType($platform_type);
                        global $SSOBSwiftTiles;
                        if (in_array($getid, $SSOBSwiftTiles)) {
                            //                    if (($ssodata) && property_exists($ssodata, 'bs_abbrev_url')) {
                            //                        $platform_type_selector = get_post_meta($getid, 'platform_type_selector', true);
                            /** to check if it is bswift tile then need to display url according to there bs_abbrev_url */
                            if ($ssodata->bs_abbrev_url == '' || $ssodata->bs_abbrev_url == 'clarity') {
                                $sso_tiles_url = get_post_meta($getid, 'bswift_sso_url1', true);
                            } else {
                                $sso_tiles_url = get_post_meta($getid, $ssodata->bs_abbrev_url, true);
                            }
                        } else {
                            $sso_tiles_url = get_post_meta($getid, 'sso_url', true);
                        }

                        global $SSOCPAllowSSOTiles;
                        global $SSOENAllowSSOTiles;
                        $sso_tiles_actual_url = '';
                        $sso_tiles_actual_headline = '';
                        $sso_tiles_actual_content = '';
                        //                    cobra special cases
                        if (in_array($getid, $SSOCPAllowSSOTiles)) {

                            if (($ssodata->is_cp_client == 1 || $ssodata->is_cp_broker == 1) && $ssodata->cp_allow_sso == 0) {
                                //                             redirect to cobra site for login
                                $sso_tiles_actual_url = $sso_tiles_url;
                                $sso_tiles_url = "/redirect-to-cobra/";
                                $sso_tiles_actual_headline = get_the_title();
                                $sso_tiles_actual_content = get_the_content();
                                $get_sso_title = "CobraPoint is Processing Your Access";
                                $get_sso_content = "Your single sign on access to CobraPoint is processing. This can take up to 5 minutes. Once complete this tile will update and you will be able to link to the application directly.";

                            }
                        } elseif (in_array($getid, $SSOENAllowSSOTiles)) {
                            if (($ssodata->is_en_client) == 1 || ($ssodata->is_en_broker) == 1 || ($ssodata->is_en_tpa_admin) == 1) {
                                //                             redirect to cobra site for login
                                $sso_tiles_url = "/redirect-to-enav/";
                            }
                        }

                        //
                        $choose_sso_image = get_post_meta($getid, 'choose_sso_image', true);
                        $imageD = wp_get_attachment_image($choose_sso_image);
                        $image = (has_post_thumbnail($getid)) ? get_the_post_thumbnail($getid, 'realty_widget_size') : '<div class="noThumb" style="height:50px;"></div>';

                        if ($noOfTiles <= 3) {
                            $widthCount = 3;
                        } else {
                            $widthCount = 4;
                        }

                        if ($noOfTiles >= $widthCount) {
                            $listItem .= '<div class="col-md-3 col-sm-6 manage_company_beneifts form-group" style="margin-right: 0px;margin-left:0px;	color: black; ">';
                        } else {
                            $listItem .= '<div class="col-md-4 col-sm-6 manage_company_beneifts form-group" style="margin-right: 0px;margin-left:0px;	color: black; ">';
                        }

                        $listItem .= '<div class="benefitRow"><a class="sso-url-' . $getid . '" href="' . $sso_tiles_url . '" target="_blank"><p class="platform_name_sso" style="display:none">' . $platform_name . '</p><div id="sso_image" style="height:auto">' . $image . '</div></a>';

                        $listItem .= '<a class="sso-url-' . $getid . '" href="' . $sso_tiles_url . '" target="_blank"><div class="compny_ben"> ' . $imageD . '<h4 class="manage_ben_title sso-title-' . $getid . '">' . $get_sso_title . '</h4></div></a>';
                        $listItem .= '<a class="sso-url-' . $getid . '" href="' . $sso_tiles_url . '" target="_blank"><span class="get_content sso-content-' . $getid . '">' . $get_sso_content . '</span></a>';
                        $listItem .= '<div class="get_plans clearfix"><a class="sso-url-' . $getid . '" href="' . $sso_tiles_url . '" target="_blank"><div class="get_plan_left"><span> Manage Plan <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptext">
                    ' . $manage_tool_tips . '</span>
                            </div></a><div class="get_plan_right"><a href="/learn/" target="_blank"><span>Learn More <i class="fa fa-angle-down" aria-hidden="true"></i></span><span class="tooltiptextR">
                            ' . $manage_tool_tips . '</span></a></div></div></div></div>';
                        /** check checkAllowSSo value for COBRA */
                        if ($sso_tiles_actual_url != '' && $sso_tiles_actual_url != $sso_tiles_url) {
                            // call js function checkAllowSSO(sso-url- . $id,   $sso_tiles_actual_url ) that will call getAllowSSO (user_id)
                            // setTimeout(3000, function () -> do-auth, getAllowSSO, if ==1  change url to $sso_tiles_actual_url)

                            checkAllowSSoValue($sso_tiles_actual_url, $getid, $sso_tiles_actual_headline, $sso_tiles_actual_content);
                        }
                    }
                }
            } // count(ssotiles)

            $listItem .= '</div>';
            $listItem .= $this->getAdditionalTiles();
            return $listItem;
        }

    }