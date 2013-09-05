<?php
class Template
{
    public function __construct($tpl_file)
    {
        if(!file_exists($tpl_file))
            throw new Exception("404");
        $this->tpl_file = $tpl_file;
    }

    /**
    *  vars : [associative array] value for the template
    *  include_globals : [boolean] export global vars too?
    */
    public function render($vars = array(), $include_globals=true)
    {
        // Magic
        extract($vars, EXTR_OVERWRITE);

        // Gloabl vars export
        if($include_globals) extract ($GLOBALS, EXTR_SKIP);

        // Output buffer
        ob_start();

        // Load template
        require($this->tpl_file);

        // Get the rendered page from the buffer
        $rendered = ob_get_contents();

        // Free the buffer
        ob_end_clean();

        return $rendered;
    }
}
