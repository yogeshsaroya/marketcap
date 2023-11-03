<?php $this->assign('title', 'Stocks'); ?>
<div class="app-content content ">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Stocks</h2>

                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-6 col-12  ">
                <div class="form-group breadcrumb-right">
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('logo'); ?></th>
                                        <th><?= $this->Paginator->sort('name'); ?></th>
                                        <th><?= $this->Paginator->sort('symbol'); ?></th>
                                        <th><?= $this->Paginator->sort('stock_price'); ?></th>
                                        <th><?= $this->Paginator->sort('market_cap'); ?></th>
                                        <th><?= $this->Paginator->sort('industry'); ?></th>
                                        <th><?= $this->Paginator->sort('country'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!$data->isEmpty()) {
                                        $num = $this->Paginator->counter('{{start}}');
                                        foreach ($data as $list) {

                                            $logo = $logo_dark = $logo_nrm =  $list->logo;
                                            if (!empty($list->logo_bright)) {
                                                $logo_dark = $logo_nrm = SITEURL . "logo/" . $list->logo_bright;
                                            }
                                            if (!empty($list->logo_dark)) {
                                                $logo_dark = SITEURL . "logo/" . $list->logo_dark;
                                            }


                                    ?>
                                            <tr>
                                                <td><img src="<?= $logo_nrm; ?>" alt="" width="64" /></td>
                                                <td><a href="<?= SITEURL . $list->slug; ?>">
                                                        <div class="company-name"><?= $list->name; ?></div>
                                                        <div class="company-code"><span class="rank d-none"></span><?= $list->symbol; ?></div>
                                                    </a></td>
                                                <td><?= strtoupper($list->symbol); ?></td>
                                                <td>$<?= num_2($list->stock_price); ?></td>
                                                <td>$<?= nice_number($list->market_cap); ?></td>
                                                <td><?= $list->industry; ?></td>
                                                <td><?= (!empty($list->country) ?  strtoupper($list->country) : null); ?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                            <div class="card-header">
                                <?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total, starting on record {{start}}, ending on {{end}}'); ?>
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                    <ul class="pagination">
                                        <?php
                                        echo $this->Paginator->first(__('First', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "btn btn-default"));
                                        echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                                        echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentLink' => true, 'currentClass' => 'active', 'currentTag' => 'a'));
                                        echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                                        echo $this->Paginator->last(__('Last', true), array('tag' => 'li', 'escape' => false), array('type' => "button", 'class' => "btn btn-default"));
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $('.st').change(function() {
        var st = $(this).val();
        var id = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '<?= SITEURL; ?>pages/update_status/',
            data: {
                status: st,
                id: id
            },
            success: function(data) {
                $("#cover").html(data);
            },
            error: function(comment) {
                $("#cover").html(comment);
            }
        });
    });
</script>