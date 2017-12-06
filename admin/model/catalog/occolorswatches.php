<?php
class ModelCatalogOccolorswatches extends Model
{
    public function installSwatchesAttribute() {
        $check_sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_image` LIKE 'product_option_value_id'";

        $query = $this->db->query($check_sql);
        if($query->rows) {
            return;
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "product_image` ADD `product_option_value_id` INT(11) NULL";
            $this->db->query($sql);
            return;
        }
    }
}