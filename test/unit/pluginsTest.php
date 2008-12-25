<?php
/**
 * Test the validity of the plugins structure
 * Prevents errors induced by chances in the organization of remote repositories
 *
 * @package    sfPluginsTest
 * @author     Laurent Bachelier
 */

// bootstrap
include(dirname(__FILE__).'/../../../../test/bootstrap/unit.php');

$t = new lime_test(1, new lime_output_color());

// We should not have any of these directories
$bad_dirs = array('branches', 'tags', 'trunk');

// And have at least these directories
$wanted_dirs = array('config', 'lib', 'modules');

// Unless it's one of these plugins
$exclude_plugins = array('sfPluginsTestPlugin');

// FIXME Should only work with Symfony 1.0
$plugdir = SF_ROOT_DIR.DIRECTORY_SEPARATOR.'plugins';
$plugins = opendir($plugdir);

// Errors
$fail = array();

while ($file = readdir($plugins))
{
  if (substr($file, 0, 1) != '.' && is_dir($plugdir.DIRECTORY_SEPARATOR.$file) && !in_array($file, $exclude_plugins))
  {
    $t->diag($file);

    foreach ($bad_dirs as $bad_dir)
    {
      if (is_dir($plugdir.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.$bad_dir))
      {
        $fail[$file] = 'has bad directory '.$bad_dir;
      }
    }

    $wanted = 0;
    foreach ($wanted_dirs as $wanted_dir)
    {
      if (is_dir($plugdir.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.$wanted_dir))
      {
        $wanted++;
      }
    }
    if ($wanted == 0)
    {
      $fail[$file] = 'zero wanted dirs';
    }

  }
}

$t->is(count($fail), 0, 'All plugins seem to be fine.');
if (count($fail))
{
  $t->diag(print_r($fail, 1));
}
