<?php
require_once 'core/init.php';
fake_404('result.php');

$title = 'Result Page';

include_once 'includes/header.php';
?>

<h1 class="center">Result Page</h1>

<h2>PRESIDENTIAL RESULT</h2><h4 class="up"><?php echo Voting::instance()->collate('president_vote', 'presidents'); ?></h4><br/><br/>

<h2>GOVERNORSHIP RESULT</h2><h4 class="up"><?php echo Voting::instance()->collate('governor_vote', 'governors'); ?></h4><br/><br/>

<h2>HOUSE OF REPRESENTATIVES RESULT</h2><h4 class="up"><?php echo Voting::instance()->collate('house_of_reps_vote', 'house_of_representatives'); ?></h4><br/><br/>

<h2>SENATE PRESIDENT RESULT</h2><h4 class="up"><?php echo Voting::instance()->collate('senate_vote', 'senators'); ?></h4><br/><br/>

<h2>STATE HOUSE OF ASSEMBLY RESULT</h2><h4 class="up"><?php echo Voting::instance()->collate('state_assembly_vote', 'state_assemblies'); ?></h4><br/><br/>
<br/><br/><br/>

<?php include_once 'includes/footer.php'; ?>