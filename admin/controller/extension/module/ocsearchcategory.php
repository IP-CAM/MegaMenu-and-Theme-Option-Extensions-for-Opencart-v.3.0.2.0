<?php
class ControllerExtensionModuleOcsearchcategory extends Controller {
    private $error = array();

    public function install() {

        $config = array(
            'module_ocsearchcategory_status' => 1,
            'module_ocsearchcategory_ajax_enabled' => 1,
            'module_ocsearchcategory_product_img' => 1,
            'module_ocsearchcategory_product_price' => 1,
            'module_ocsearchcategory_loader_img' => 'catalog/AjaxLoader.gif'
        );
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_ocsearchcategory', $config);

    }

    public function index() {
        $this->load->language('extension/module/ocsearchcategory');

        $this->document->setTitle($this->language->get('page_title'));

        $this->load->model('setting/setting');
        $this->load->model('tool/image');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $post_data = $this->request->post;

            $this->model_setting_setting->editSetting('module_ocsearchcategory', $post_data);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/ocsearchcategory', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/ocsearchcategory', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_ocsearchcategory_status'])) {
            $data['module_ocsearchcategory_status'] = $this->request->post['module_ocsearchcategory_status'];
        } else {
            $data['module_ocsearchcategory_status'] = $this->config->get('module_ocsearchcategory_status');
        }

        if (isset($this->request->post['module_ocsearchcategory_ajax_enabled'])) {
            $data['module_ocsearchcategory_ajax_enabled'] = $this->request->post['module_ocsearchcategory_ajax_enabled'];
        } else {
            $data['module_ocsearchcategory_ajax_enabled'] = $this->config->get('module_ocsearchcategory_ajax_enabled');
        }

        if (isset($this->request->post['module_ocsearchcategory_product_img'])) {
            $data['module_ocsearchcategory_product_img'] = $this->request->post['module_ocsearchcategory_product_img'];
        } else {
            $data['module_ocsearchcategory_product_img'] = $this->config->get('module_ocsearchcategory_product_img');
        }

        if (isset($this->request->post['module_ocsearchcategory_product_price'])) {
            $data['module_ocsearchcategory_product_price'] = $this->request->post['module_ocsearchcategory_product_price'];
        } else {
            $data['module_ocsearchcategory_product_price'] = $this->config->get('module_ocsearchcategory_product_price');
        }

        if (isset($this->request->post['module_ocsearchcategory_loader_img'])) {
            $data['module_ocsearchcategory_loader_img'] = $this->request->post['module_ocsearchcategory_loader_img'];
        } else {
            $data['module_ocsearchcategory_loader_img'] = $this->config->get('module_ocsearchcategory_loader_img');
        }

        if (isset($this->request->post['module_ocsearchcategory_loader_img']) && is_file(DIR_IMAGE . $this->request->post['module_ocsearchcategory_loader_img'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['module_ocsearchcategory_loader_img'], 50, 50);
        } elseif (is_file(DIR_IMAGE . $this->config->get('module_ocsearchcategory_loader_img'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('module_ocsearchcategory_loader_img'), 50, 50);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 50, 50);
        }
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 50, 50);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/ocsearchcategory', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/ocsearchcategory')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}