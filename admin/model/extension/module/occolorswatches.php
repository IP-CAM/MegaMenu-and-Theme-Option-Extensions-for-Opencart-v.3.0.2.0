<?php
class ModelExtensionModuleOccolorswatches extends Model
{
    public function installSwatchesAttribute() {
        $check_sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_image` LIKE 'option_value_id'";

        $query = $this->db->query($check_sql);
        if($query->rows) {
            return;
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "product_image` ADD `option_value_id` INT(11) NULL";
            $this->db->query($sql);
            return;
        }

        $check_sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'use_swatches'";

        $query = $this->db->query($check_sql);
        if($query->rows) {
            return;
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "product` ADD `use_swatches` TINYINT(1) DEFAULT '0'";
            $this->db->query($sql);
            return;
        }
    }
}