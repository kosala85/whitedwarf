<?php

namespace Domain\Core\Abstracts;

abstract class LogicAbstract
{
    private $db;
    protected $session;


    /**
     * LogicAbstract constructor.
     */
    public function __construct()
    {
        // get a reference to DatabaseAdapter
        // (NOTE: only use the db object to manage transactions in the Logic layer and avoid using it to issue database
        //        queries directly. Do those in the Repository layer.)
        $this->db = $GLOBALS['db'];
        
        // get a reference to the session
        $this->session = $GLOBALS['session'];
    }


    /**
     * To give the domain access to db instance but to limit its ability to that of only managing transactions.
     *
     * @param $function
     * @return mixed
     * @throws \Exception
     */
    protected function wrapInTransaction(\Closure $function)
    {
        $this->db->transBegin();

        try
        {
            $result = $function();

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }

}