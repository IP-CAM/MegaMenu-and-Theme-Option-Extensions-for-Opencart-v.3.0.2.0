<?php
class ControllerProductOcquickview extends Controller
{
    public function index() {
        $json = array();

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $data = $this->loadProduct($product_id);

        if(!$json) {
            if($data) {
                $json['html'] = $this->load->view('product/ocquickview/product', $data);
                $json['success'] = true;
            } else {
                $json['success'] = false;
                $json['html'] = "There is no product";
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function seoview() {
        $json = array();

        $this->load->model('catalog/ocquickview');

        if(!$json) {
            if (isset($this->request->get['ourl'])) {
                $seo_url = $this->request->get['ourl'];

                $product_id = $this->model_catalog_ocquickview->getProductBySeoUrl($seo_url);

                $data = $this->loadProduct($product_id);

                if($data) {
                    $json['html'] = $this->load->view('product/ocquickview/product', $data);
                    $json['success'] = true;
                } else {
                    $json['success'] = false;
                    $json['html'] = "There is no product";
                }
            } else {
                $json['success'] = false;
                $json['html'] = "There is no product";
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function loadProduct($product_id) {
        $this->load->language('product/product');
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if($product_info) {
            $data['product_name'] = $product_info['name'];

            $data['heading_title'] = $product_info['name'];

            /* Zoom & Swatches */
            $store_id = $this->config->get('config_store_id');
            $use_swatches = (int) $this->config->get('module_octhemeoption_use_swatches')[$store_id];
            $use_zoom = (int) $this->config->get('module_octhemeoption_use_zoom')[$store_id];

            $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
            $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

            $this->load->model('catalog/review');

            $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

            $data['product_id'] = (int) $product_id;
            $data['manufacturer'] = $product_info['manufacturer'];
            $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $data['model'] = $product_info['model'];
            $data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];
            $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

            if ($product_info['quantity'] <= 0) {
                $data['stock'] = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $data['stock'] = $product_info['quantity'];
            } else {
                $data['stock'] = $this->language->get('text_instock');
            }

            $this->load->model('tool/image');

            if ($product_info['image']) {
                $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
            } else {
                $data['popup'] = '';
            }

            if ($product_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
            } else {
                $data['thumb'] = '';
            }

            if($use_swatches) {
                $data['use_swatches'] = true;
                $data['icon_swatches_width'] = $this->config->get('module_octhemeoption_swatches_width')[$store_id];
                $data['icon_swatches_height'] = $this->config->get('module_octhemeoption_swatches_height')[$store_id];
                $data['swatches_option'] = $this->config->get('module_octhemeoption_swatches_option')[$store_id];
            } else {
                $data['use_swatches'] = false;
            }

            if($use_zoom) {
                $data['use_zoom'] = true;

                if ($product_info['image']) {
                    $data['small_image'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'));
                } else {
                    $data['small_image'] = '';
                }

                if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/oczoom/zoom.css')) {
                    $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/oczoom/zoom.css');
                } else {
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/oczoom/zoom.css');
                }

                $data['popup_dimension'] = array(
                    'width' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'),
                    'height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')
                );

                $data['thumb_dimension'] = array(
                    'width' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'),
                    'height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height')
                );

                $data['small_dimension'] = array(
                    'width' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'),
                    'height' => $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height')
                );

                $bg_status = (int) $this->config->get('module_octhemeoption_zoom_background_status')[$store_id];
                if($bg_status) {
                    $zoom_bg_status = true;
                } else {
                    $zoom_bg_status = false;
                }

                $title_status = (int) $this->config->get('module_octhemeoption_zoom_title')[$store_id];
                if($title_status) {
                    $zoom_title_status = true;
                } else {
                    $zoom_title_status = false;
                }

                $data['zoom_config'] = array(
                    'position' => $this->config->get('module_octhemeoption_zoom_position')[$store_id],
                    'space' => $this->config->get('module_octhemeoption_zoom_space')[$store_id],
                    'bg_status' => $zoom_bg_status,
                    'bg_color' => '#' . $this->config->get('module_octhemeoption_zoom_background_color')[$store_id],
                    'bg_opacity' => $this->config->get('module_octhemeoption_zoom_background_opacity')[$store_id],
                    'title_status' => $zoom_title_status
                );
            } else {
                $data['use_zoom'] = false;
            }

            $data['images'] = array();

            $results = $this->model_catalog_product->getProductImages($product_id);

            foreach ($results as $result) {
                $data['images'][] = array(
                    'product_option_value_id' => $result['product_option_value_id'],
                    'product_image_option' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height')),
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                    'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
                );
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $data['price'] = false;
            }

            if ((float)$product_info['special']) {
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $data['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            } else {
                $data['tax'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($product_id);

            $data['discounts'] = array();

            foreach ($discounts as $discount) {
                $data['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                );
            }

            $data['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
                $product_option_value_data = array();

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        $product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id'         => $option_value['option_value_id'],
                            'name'                    => $option_value['name'],
                            'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price'                   => $price,
                            'price_prefix'            => $option_value['price_prefix']
                        );
                    }
                }

                $data['options'][] = array(
                    'product_option_id'    => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id'            => $option['option_id'],
                    'name'                 => $option['name'],
                    'type'                 => $option['type'],
                    'value'                => $option['value'],
                    'required'             => $option['required']
                );
            }

            if ($product_info['minimum']) {
                $data['minimum'] = $product_info['minimum'];
            } else {
                $data['minimum'] = 1;
            }

            $data['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $data['review_guest'] = true;
            } else {
                $data['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
            $data['rating'] = (int)$product_info['rating'];

            // Captcha
            if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
            } else {
                $data['captcha'] = '';
            }

            $data['share'] = $this->url->link('product/product', 'product_id=' . (int) $product_id);

            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id);

            $data['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag'  => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }

            $data['recurrings'] = $this->model_catalog_product->getProfiles($product_id);

            $this->model_catalog_product->updateViewed($product_id);
        } else {
            $data = false;
        }

        return $data;
    }

    public function container() {
        $this->load->language('product/ocquickview');
        $data['ocquickview_loader_img'] = $this->config->get('config_url') . 'image/' . $this->config->get('module_octhemeoption_loader_img');
        $data['ocquickview_status'] = (int) $this->config->get('module_octhemeoption_quickview');

        return $this->load->view('product/ocquickview/qvcontainer', $data);
    }

    public function appendcontainer() {
        $this->response->setOutput($this->container());
    }
}