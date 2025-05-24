<?php
include('../includes/header.php');
include('../config.php');

// Test paketlerini getirme
$query = "SELECT tp.*, p.project_name, eng.full_name as responsible_engineer
          FROM test_packages tp
          JOIN projects p ON tp.project_id = p.id
          JOIN personnel eng ON tp.responsible_engineer_id = eng.id
          ORDER BY tp.planned_test_date ASC";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid">
    <h2 class="mb-4">Test Paket Yönetimi</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Test Paketi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $count_query = "SELECT COUNT(*) as total FROM test_packages";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['total'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tamamlanan Testler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                    $completed_query = "SELECT COUNT(*) as completed FROM test_packages WHERE status='approved'";
                                    $completed_result = mysqli_query($conn, $completed_query);
                                    $completed_row = mysqli_fetch_assoc($completed_result);
                                    echo $completed_row['completed'];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Test Paket Listesi</h6>
            <div>
                <a href="add_test_package.php" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Yeni Paket
                </a>
                <a href="test_reports.php" class="btn btn-info btn-sm">
                    <i class="fas fa-file-alt"></i> Raporlar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="testPackagesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Paket Adı</th>
                            <th>Proje</th>
                            <th>Test Ortamı</th>
                            <th>Planlanan Tarih</th>
                            <th>Gerçekleşen Tarih</th>
                            <th>Sorumlu</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['package_name'] ?></td>
                            <td><?= $row['project_name'] ?></td>
                            <td><?= strtoupper($row['test_medium']) ?></td>
                            <td><?= date('d.m.Y', strtotime($row['planned_test_date'])) ?></td>
                            <td>
                                <?= $row['actual_test_date'] ? date('d.m.Y', strtotime($row['actual_test_date'])) : '-' ?>
                            </td>
                            <td><?= $row['responsible_engineer'] ?></td>
                            <td>
                                <?php 
                                    $status_badge = [
                                        'preparation' => 'secondary',
                                        'ready' => 'info',
                                        'testing' => 'warning',
                                        'completed' => 'primary',
                                        'approved' => 'success'
                                    ];
                                ?>
                                <span class="badge badge-<?= $status_badge[$row['status']] ?>">
                                    <?= strtoupper(str_replace('_', ' ', $row['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_test_package.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Detay">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="test_results.php?package_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Sonuçlar">
                                    <i class="fas fa-file-signature"></i>
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

<?php include('../includes/footer.php'); ?>