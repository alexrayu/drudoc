## Entity Types:
<?php $i = 0; ?>
<?php foreach ($data as $class_info) { ?>


<?php $i++; ?>
### <?php echo $i; ?>. <?php echo _d($class_info['name']); ?>
  - **Namespace:** `<?php echo $class_info['namespace']; ?>`.
<?php if (!empty($class_info['summary'])) { ?>
  - **Summary:** <?php echo _d($class_info['summary']); ?>
<?php } ?>
<?php if (!empty($class_info['description'])) { ?>
  - **Description:** <?php echo _d($class_info['description']); ?>
<?php } ?>
<?php } ?>
