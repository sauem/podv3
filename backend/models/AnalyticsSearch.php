<?php


namespace backend\models;


use common\helper\Helper;

class AnalyticsSearch extends ContactsModel
{

    public function search($queryParams)
    {
        $query = ContactsModel::find();

        if (!empty($queryParams['marketer']) || !empty($queryParams['sale'])) {
            $marketers = $queryParams['marketer'];
            $sales = $queryParams['sale'];
            $query->join('INNER JOIN', 'landing_pages as page', 'page.link = contacts.short_link');
            if ($marketers) {
                foreach ($marketers as $k => $marketer) {
                    if (!$marketer === "null") {
                        $marketers[$k] = null;
                    }
                }
                $query->filterWhere(['IN', 'page.marketer', $marketers]);
            }

            if ($sales) {
                $query->join('INNER JOIN', 'contacts_assignment as sale', 'sale.contact_phone = contacts.phone')
                    ->filterWhere(['IN', 'sale.user_id', $sales]);
            }
        }
        if (!empty($queryParams['type'])) {
            $types = $queryParams['type'];

            foreach ($types as $k => $type) {
                if (!$type === "null") {
                    $types[$k] = null;
                }
            }
            $query->filterWhere(['contacts.type' => $types]);
        }
        if (!empty($queryParams['product'])) {
            $products = $queryParams['product'];
            $query->join('INNER JOIN', 'orders', 'orders.code=contacts.code')
                ->join('INNER JOIN', 'orders_items', 'orders.id=orders_items.order_id')
                ->filterWhere(['orders_items.product_sku' => $products]);
        }
        if (!empty($queryParams['date'])) {
            $date = explode('-', $queryParams['date']);
            $startDate = trim($date[0]);
            $endDate = trim($date[1]);

            $startDate = \DateTime::createFromFormat('m/d/Y', $startDate)->format('m/d/Y');
            $startDate = strtotime($startDate);

            $endDate = \DateTime::createFromFormat('m/d/Y', $endDate)->format('m/d/Y');
            $endDate = strtotime($endDate);

            $query->andFilterWhere(['between', 'contacts.created_at', $startDate, $endDate]);
        } else {
            $startDate = strtotime(' -14 days');
            $endDate = time();
            $query->andFilterWhere(['between', 'contacts.created_at', $startDate, $endDate]);
        }

        $query->andFilterWhere(['IN', 'contacts.status', [
            ContactsModel::_OK,
            ContactsModel::_CALLBACK,
            ContactsModel::_PENDING,
            ContactsModel::_NEW,
            ContactsModel::_CANCEL,
            ContactsModel::_SKIP
        ]]);
        $query->select([
            'COUNT(*) as `C3`',
            'SUM(IF (contacts.status = \'ok\', 1, 0) ) as C8',
            'FROM_UNIXTIME(contacts.updated_at, \'%d/%m/%Y\') day',
            // 'MONTH(FROM_UNIXTIME(contacts.updated_at)) as month',
        ])->groupBy(['day']);

        return $query->asArray()->all();
    }

    static function getC3($query)
    {
        $query->filterWhere(['IN', 'contacts.status', [
            ContactsModel::_OK,
            ContactsModel::_CALLBACK,
            ContactsModel::_PENDING,
            ContactsModel::_NEW,
            ContactsModel::_CANCEL,
            ContactsModel::_SKIP
        ]]);
        return $query->count();
    }

    static function getC8($query)
    {
        $query->filterWhere(['IN', 'contacts.status', [
            ContactsModel::_OK,
        ]]);

        return $query->count();
    }
}