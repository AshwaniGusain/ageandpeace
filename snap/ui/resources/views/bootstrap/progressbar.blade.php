<div<?php if ( ! empty($id)) : ?> id="<?=$id?>"<?php endif; ?> class="progress">
    <div<?php if ( ! empty($id)) : ?> id="<?=$id?>-bar"<?php endif; ?> class="progress-bar<?php if ( ! empty($striped)) : ?> progress-bar-striped<?php endif; ?><?php if ( ! empty($striped)) : ?> progress-bar-animated<?php endif; ?>" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent?>%"></div>
</div>
