
## Classes:
<?php $i = 0; ?>
<?php foreach ($data['classes'] as $class_info) { ?>


<?php $i++; ?>
### <?php echo $i; ?>. <?php _d($class_info['name']); ?>
  - **Namespace:** `<?php echo $class_info['namespace']; ?>`
<?php if (!empty($class_info['summary'])) { ?>
  - **Summary:** <?php echo _d($class_info['summary']); ?>
<?php } ?>
<?php if (!empty($class_info['description'])) { ?>
  - **Description:** <?php echo _d($class_info['description']); ?>
<?php } ?>
<?php } ?>
