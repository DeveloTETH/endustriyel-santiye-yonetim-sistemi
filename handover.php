<?php
include('../includes/header.php');
include('../config.php');

// Teslimat belgelerini getirme
$query = "SELECT hd.*, p.project_name, resp.full_name as responsible, 
                 client.full_name as client_representative
          FROM handover_documents hd
          JOIN projects p ON hd.project_id = p.id
          JOIN personnel resp ON hd.responsible_person_id = resp.id
          LEFT JOIN personnel client ON hd.client_representative_id = client.id
          ORDER BY hd.approval_date DESC, hd.document_number ASC";
$result = mysqli_query($conn, $query);

// Punch listelerini getirme
$punch_query = "SELECT pl.*, p.project_name, raised.full_name as raised_by_name,
                       assigned.full_name as assigned_to_name
                FROM punch_list pl
                JOIN projects p ON pl.project_id = p.id
                JOIN personnel raised ON pl.raised_by = raised.id
                LEFT JOIN personnel assigned ON pl.assigned_to = assigned.id
                WHERE pl.status != 'closed'
                ORDER BY pl.priority DESC, pl.target_completion_date ASC";
$punch_result = mysqli_query($conn, $punch_query);
?>

<div class="container-fluid">
    <h2 class="mb-4">Teslimat (Handover) Yönetimi</h2>
    
    <ul class="nav nav-tabs" id="handoverTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                Teslimat Belgeleri
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="punchlist-tab" data-toggle="tab" href="#punchlist" role="tab">
                Punch Listesi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab">
                Raporlar
            </a>
        </li>
    </ul>
    
    <div class="tab-content" id="handoverTabContent">
        <div class="tab-pane fade show active" id="documents" role="tabpanel">
            <div class="card shadow mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Teslimat Belgeleri</h6>
                    <div>
                        <a href="add_document.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Yeni Belge
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="documentsTable">
                            <thead>
                                <tr>
                                    <th>Belge No</th>
                                    <th>Proje</th>
                                    <th>Tip</th>
                                    <th>Başlık</th>
                                    <th>Revizyon</th>
                                    <th>Onay Tarihi</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['document_number'] ?></td>
                                    <td><?= $row['project_name'] ?></td>
                                    <td><?= strtoupper($row['document_type']) ?></td>
                                    <td><?= $row['title'] ?></td>
                                    <td><?= $row['revision'] ?: '-' ?></td>
                                    <td>
                                        <?= $row['approval_date'] ? date('d.m.Y', strtotime($row['approval_date'])) : '-' ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $status_badge = [
                                                'draft' => 'secondary',
                                                'under_review' => 'info',
                                                'approved' => 'primary',
                                                'submitted' => 'warning',
                                                'accepted' => 'success'
                                            ];
                                        ?>
                                        <span class="badge badge-<?= $status_badge[$row['status']] ?>">
                                            <?= strtoupper(str_replace('_', ' ', $row['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_document.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($row['file_path']): ?>
                                            <a href="<?= $row['file_path'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="punchlist" role="tabpanel">
            <div class="card shadow mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Punch Listesi (Açık Maddeler)</h6>
                    <div>
                        <a href="add_punch_item.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Yeni Madde
                        </a>
                        <a href="closed_punch_items.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-archive"></i> Kapalı Maddeler
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="punchListTable">
                            <thead>
                                <tr>
                                    <th>Madde No</th>
                                    <th>Proje</th>
                                    <th>Açıklama</th>
                                    <th>Kategori</th>
                                    <th>Öncelik</th>
                                    <th>Atanan</th>
                                    <th>Hedef Tarih</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($punch_result)): ?>
                                <tr>
                                    <td><?= $row['item_number'] ?></td>
                                    <td><?= $row['project_name'] ?></td>
                                    <td><?= substr($row['description'], 0, 50) ?>...</td>
                                    <td><?= ucfirst($row['category']) ?></td>
                                    <td>
                                        <?php 
                                            $priority_class = [
                                                'low' => 'success',
                                                'medium' => 'info',
                                                'high' => 'warning',
                                                'critical' => 'danger'
                                            ];
                                        ?>
                                        <span class="badge badge-<?= $priority_class[$row['priority']] ?>">
                                            <?= strtoupper($row['priority']) ?>
                                        </span>
                                    </td>
                                    <td><?= $row['assigned_to_name'] ?: '-' ?></td>
                                    <td>
                                        <?= date('d.m.Y', strtotime($row['target_completion_date'])) ?>
                                        <?php 
                                            $today = new DateTime();
                                            $target_date = new DateTime($row['target_completion_date']);
                                            if($target_date < $today && $row['status'] != 'completed') {
                                                echo '<span class="badge badge-danger ml-2">GEÇ</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $status_class = [
                                                'open' => 'secondary',
                                                'in_progress' => 'primary',
                                                'completed' => 'success',
                                                'verified' => 'info'
                                            ];
                                        ?>
                                        <span class="badge badge-<?= $status_class[$row['status']] ?>">
                                            <?= strtoupper(str_replace('_', ' ', $row['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_punch_item.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="update_punch_item.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="reports" role="tabpanel">
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teslimat Raporları</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Belge Durum Raporu</h5>
                                    <p class="card-text">Teslimat belgelerinin durumuna göre rapor</p>
                                    <a href="document_status_report.php" class="btn btn-primary">Raporu Görüntüle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Punch Listesi Analizi</h5>
                                    <p class="card-text">Kategori ve önceliğe göre punch maddeleri</p>
                                    <a href="punch_analysis_report.php" class="btn btn-success">Raporu Görüntüle</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Teslimat Özeti</h5>
                                    <p class="card-text">Proje bazında teslimat durumu</p>
                                    <a href="handover_summary.php" class="btn btn-info">Raporu Görüntüle</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>