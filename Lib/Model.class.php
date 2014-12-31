<?php
/**
 * Model.class.php
 *
 *   author:  hanyang (han2008yang@163.com)
 *   create time: 2014-12-30 13:40:23
 *
 */
class Model {

    /**
     * $_db
     * 数据库对象
     *
     * @var mysql
     */
    protected $_db;

    /**
     * $_table_name
     * 表名
     *
     * @var string
     */
    protected $_table_name;

    /**
     * __construct
     *
     * @param string $table_name
     */
    function __construct($table_name = '') {
        if ($table_name) {
            $this->_table_name = $table_name;
        } else {
            $this->_table_name = parse_name(substr(get_class($this), 0, -5));
        }
        $this->_db = new Mysql();
    }

    /**
     * fetch_one
     *
     * @param  mixed $where
     * @return array
     */
    public function fetch_one($where) {

        $sql = 'SELECT * FROM ' . $this->_table_name;

        if ($where) {
            $sql .= ' WHERE ' . $this->__build_condition($where);
        }

        $sql .= ' LIMIT 1';

        return $this->_db->fetchOne($sql);
    }

    /**
     * fetch_all
     *
     * @param  mixed $where
     * @param  string $order
     * @param  string $limit
     * @return array
     */
    public function fetch_all($where = '', $order = '', $limit = '') {

        $sql = 'SELECT * FROM ' . $this->_table_name;

        if ($where) {
            $sql .= ' WHERE ' . $this->__build_condition($where);
        }

        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        return $this->_db->fetchAll($sql);
    }

    /**
     * insert
     * 插入数据
     *
     * @param  array $data
     * @return mixed
     */
    public function insert($data) {
        $data_sql = array();

        foreach ($data as $volumn => $value) {
            if (!is_numeric($value)) {
                $value = '"' . addslashes($value) . '"';
            }
            $data_sql[] = '`' . $volumn . '` = ' . $value;
        }

        $sql = 'INSERT INTO ' . $this->_table_name . ' SET ' . implode(', ', $data_sql);
        $this->_db->query($sql);

        return $this->_db->insertId();

    }

    /**
     * update
     *
     * @param  array $data
     * @param  string $where
     * @return bool
     */
    public function update($data, $where = '') {
        $data_sql = array();

        foreach ($data as $volumn => $value) {
            if (!is_numeric($value)) {
                $value = '"' . addslashes($value) . '"';
            }
            $data_sql[] = '`' . $volumn . '` = ' . $value;
        }

        $sql = 'UPDATE ' . $this->_table_name . ' SET ' . implode(', ', $data_sql);

        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        return $this->_db->query($sql);

    }

    /**
     * delete
     *
     * @param  string $where
     * @return bool
     */
    public function delete($where) {
        $sql = 'DELETE FROM ' . $this->_table_name . ' WHERE ' . $where;
        return $this->_db->query($sql);
    }

    /**
     * count
     *
     * @param  string $where
     * @return integer
     */
    public function count($where = '') {
        $sql = 'SELECT count(*) AS num FROM ' . $this->_table_name;

        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        $result = $this->_db->fetchOne($sql);
        return intval($result['num']);
    }

    /**
     * __build_condition
     *
     * @param  mixed $where
     * @return string
     */
    private function __build_condition($where) {

        $condition = array();

        if (is_array($where)) {
            foreach ($where as $volumn => $value) {
                if (!is_numeric($value)) {
                    $value = '"' . addslashes($value) . '"';
                }
                $condition[] = '`' . $volumn . '` = ' . $value;
            }

            $where = implode(' AND ', $condition);
        }

        return $where;
    }

}