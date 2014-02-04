<?php

namespace CEIT\core
{
    interface ICrud
    {
        public function index(); // select
        public function create(); // insert
        public function detail($id); // select one
        public function update($id); // update
        public function delete($id); // delete
    }
}

