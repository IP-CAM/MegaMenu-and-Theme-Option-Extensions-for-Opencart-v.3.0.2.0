<?php 
class ModelCatalogOcproductrotator extends Model
{
	public function getProductRotatorImage($product_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = " . (int) $product_id . " AND is_rotator = 1";

		$query = $this->db->query($sql);
		if($query->num_rows) {
			return $query->row['image'];
		} else {
			return false;
		}
	}
}