<?php $title = 'Fork'; ?>

<?php ob_start(); ?>

    <div>
        <img src="resources/img/fork.svg" alt="Fork"/>
        <h1>Fork</h1>
        <p>Page de base du framework</p>
    </div>

<?php $content = ob_get_clean(); ?>

<?php require_once 'view/base.php'; ?>