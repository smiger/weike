<?php
namespace app\common\service;

/**
 * Charge Service
 */
class Charge extends Common {
    /**
     * 添加记录
     * @param  array $Cardmin 记录信息
     * @return array
     */
    public function add($data) {
        $AboutDown = $this->getD();

        $as = $AboutDown->add($data);

        if (false === $as) {
            return $this->errorResultReturn('系统出错了！');
        }

        return $this->resultReturn(true);
    }

    /**
     * 更新记录信息
     * @return
     */
    public function update($data) {
        $AboutDown = $this->getD();

        if (false === $AboutDown->save($data)) {
            return $this->errorResultReturn('系统错误！');
        }

        return $this->resultReturn(true);
    }

    /**
     * 是否存在记录
     * @param  int     $id 记录id
     * @return boolean
     */
    public function exist($id) {
        return !is_null($this->getM()->getById($id));
    }

    /**
     * 删除账户并且删除数据表
     * @param  int     $id 需要删除账户的id
     * @return boolean
     */
    public function delete($ids) {
        $Dao = $this->getD();

        $ids = is_array($ids) ? $ids : array($ids);
        $delStatus = $Dao->delete(implode(',', $ids));

        if (false === $delStatus) {
            return $this->errorResultReturn('系统错误！');
        }

        return $this->resultReturn(true);
    }

    protected function getModelName() {
        return 'CreditRecord';
    }
}
