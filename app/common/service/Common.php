<?php
namespace app\common\service;

use think\Loader;

/**
 * Common Service
 */
abstract class Common {
    /**
     * 根据ID获取数据
     * @param  int $id
     * @return array
     */
    public function getById($id) {
        return $this->getM()->getById($id);
    }

    /**
     * 得到数据行数
     * @param  array $where
     * @return int
     */
    public function getCount(array $where, $filter = null) {
        $M = $this->getM()->where($where);

        if($filter && is_array($filter)) {
            $flag = false;

            if(isset($filter['_string']) && $filter['_string'])
            {
                foreach ($filter['_string'] as $value)
                {
                    $M = $M->where($value);
                }
                $flag = true;
            }

            if(isset($filter['_array']) && $filter['_array'])
            {
                $M = $M->where($filter['_array']);
                $flag = true;
            }

            if(isset($filter['_like']) && $filter['_like'])
            {
                $M = $M->where($filter['_like']);
                $flag = true;
            }

            if(!$flag && $filter)
            {
                $M = $M->where($filter);
            }
        }

        $count = $M->count();
        //echo $M->getLastSql();
        //exit;

        return $count;
    }

    /**
     * 得到分页数据
     * @param  array $where    分页条件
     * @param  int   $pageSize 行数
     * @param  int   $listRows 总记录数
     * @return array
     */
    public function getPagination($where, $fields, $order, $pageSize, $total = null, $filter = null) {
        // 是否关联模型
        $M = $this->isRelation() ? $this->getD()->relation(true)
                                 : $this->getM();
        // 需要查找的字段
        if (isset($fields)) {
            $M = $M->field($fields);
        }

        // 条件查找
        if (isset($where)) {
            $M = $M->where($where);
        }

        if($filter && is_array($filter)) {
            $flag = false;

            if(isset($filter['_string']) && $filter['_string'])
            {
                foreach ($filter['_string'] as $value)
                {
                    $M = $M->where($value);
                }
                $flag = true;
            }

            if(isset($filter['_array']) && $filter['_array'])
            {
                $M = $M->where($filter['_array']);
                $flag = true;
            }

            if(isset($filter['_like']) && $filter['_like'])
            {
                $M = $M->where($filter['_like']);
                $flag = true;
            }

            if(!$flag && $filter)
            {
                $M = $M->where($filter);
            }
        }

        // 数据排序
        if (isset($order)) {
            $M = $M->order($order);
        }

        // 查询限制
        if (isset($pageSize) && isset($total)) {
            return $M->paginate($pageSize, $total);
        }

        return $M->paginate($pageSize);
    }

    /**
     * 返回结果值
     * @param  int   $status
     * @param  fixed $data
     * @return array
     */
    protected function resultReturn($status, $data) {
        return array('status' => $status,
                     'data' => $data);
    }

    /**
     * 返回错误的结果值
     * @param  string $error 错误信息
     * @return array         带'error'键值的数组
     */
    protected function errorResultReturn($error) {
        return $this->resultReturn(false, array('error' => $error));
    }

    /**
     * 得到M
     * @return Model
     */
    protected function getM() {
        return Loader::model($this->getModelName());
    }

    /**
     * 得到D
     * @return Model
     */
    protected function getD() {
        return Loader::model($this->getModelName());
    }

    /**
     * 是否关联查询
     * @return boolean
     */
    protected function isRelation() {
        return false;
    }

    /**
     * 得到模型的名称
     * @return string
     */
    protected abstract function getModelName();
}
