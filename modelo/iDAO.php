<?php

interface iDAO {

    public function getById($id);
    public function getAll($filter);
    public function insert($data);
    public function update($data);
    public function remove($id);

}