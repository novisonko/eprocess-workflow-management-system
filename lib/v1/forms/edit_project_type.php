<?php

/*////////////////////////////////////////////////////////////////////////////////////////////////////////////

@author Novi Sonko 2012

////////////////////////////////////////////////////////////////////////////////////////////////////////*/


class edit_project_type extends html_form_adapter
{
    protected $selector;

    
    public function initialize()
    {
        global $c, $t;


        parent::initialize();

        $this->selector= new select_department();
        $this->selector->initialize();
    }
    
    

    public function config()
    {
        global $t, $q;

    
        parent::config();

        $this->selector->config();


        $this->reference= $this->i_var['form_name']= "edit_project_type";

        $this->set_title($t->create." | ".$t->edit, "h2");

        if (($_GET['id'] && ($this->id= $this->require_id("numeric", $_GET['id'])) && $this->id)
                || ($_REQUEST['id_proj_type'] && ($this->id= $this->require_id("numeric", $_REQUEST['id_proj_type'])) && $this->id)) {
            $this->data_source= "select_project_type1";
            $this->has['update_data_from_global']= false;
            $q->set_filter("id_proj_type=".$this->id);
        } else {
            $this->is['new']= true;
        }

        $this->define_form();
    }
    
    
    
    
    
    public function define_form()
    {
        global $s, $u, $t;
    
        $fields=array();

        $fields[]="name_proj_type";

        $this->make_sections("input_text", 1);

        if ($this->id) {
            $fields[]="id_proj_type";
            $this->make_sections("hidden", 1);
        }
                
        $this->set_fields($fields);

        $this->fields['id_proj_type']= $this->id;
    }
    
    
    
    
    public function validate_data()
    {
        global $s, $c;
    }
    
    
    
    
    public function onsubmit()
    {
        global $q;
        


        if (($_REQUEST['form_name'] == $this->i_var['form_name'])
                && $this->is_validated()) {
            if (is_numeric($_REQUEST['status'])) {
                $temp= $this->shelf['status'][($_REQUEST['status'])];
                $temp = explode("_", $temp);
            
                $_REQUEST['status']= $temp[0];
            }
        
        
            $this->var_to_queries($_REQUEST);
                                        
            if ($_REQUEST["create_".$this->subject]) {
                if (!$q->sql_action("insert_project_type1")) {
                    $this->throw_msg(
                        "error",
                        "create_failed",
                                    "#met #onsubmit"
                    );
                    
                    $this->is['submitted']= true;
                    $this->set_has("update_data_from_global", true);
                    return;
                }

                $this->throw_msg(
                "confirm",
                "record_created",
                                    "#met #onsubmit"
            );
            }
            
            
            if ($_REQUEST["save_".$this->subject]) {
                $q->set_var("id_proj_type", $this->id);
                    
                if (!$q->sql_action("delete_from_project_type1") || !$q->sql_action("save_project_type1")) {
                    $this->throw_msg(
                        "error",
                        "save_failed",
                                    "#met #onsubmit"
                    );
                    
                    $this->is['submitted']= true;
                    $this->set_has("update_data_from_global", true);
                    return;
                }

                $this->throw_msg(
                "confirm",
                "record_saved",
                                    "#met #onsubmit"
            );
            }
            
            
            if ($_REQUEST["yes_delete_".$this->subject]) {
                $q->set_var("id_proj_type", $this->id);
                    
                if (!$q->sql_action("delete_from_project_type1")) {
                    $this->throw_msg(
                        "error",
                        "delete_failed",
                                    "#met #onsubmit"
                    );
                    
                    $this->is['submitted']= true;
                    $this->set_has("update_data_from_global", true);
                    return;
                }

                $this->throw_msg(
                "confirm",
                "record_deleted",
                                    "#met #onsubmit"
            );
                $this->is['new']= true;
            }
        } elseif (($_REQUEST['form_name'] == $this->i_var['form_name']) && !$this->in_cancel_mode()) {
            $this->is['submitted']= true;
            $this->set_has("update_data_from_global", true);
        }
    }

    
    

        
    
    
    public function set_data()
    {
        global $q;


        if (!$this->has['abort']) {
            parent::set_data();
        }
        
        $q->clear("all");
        $this->selector->set_data();

        if ($this->data['id_dept']) {
            $this->selector->set_selected($this->data['id_dept']);
        }
    }
    
    
    
    
    
    public function display_skeleton()
    {
        global $t;

    
        echo <<<HTML

<{$this->sections_tag} class="{$this->i_var['sections_class']}">
<p class="form_label">{$t->name_dept}:</p>
HTML;

        $this->selector->display();

        echo "</{$this->sections_tag}>";
    }
}
