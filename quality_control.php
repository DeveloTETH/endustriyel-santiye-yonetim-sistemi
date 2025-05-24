<?php
include('../includes/header.php');
include('../config.php');

// Kalite kontrol kayıtlarını getirme
$query = "SELECT qc.*, p.project_name, per.full_name as responsible 
          FROM quality_control qc
          JOIN projects p ON qc.project_id = p.id
          JOIN personnel per ON qc.responsible_id = per.id
          ORDER BY qc.control_date DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h2>Kalite Kontrol Kayıtları</h2>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="add_qc.php" class="btn btn-success">Yeni Kontrol Ekle</a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="qc_reports.php" class="btn btn-info">Raporlar</a>
                    <a href="non_conformities.php" class="btn btn-warning">Uygunsuzluklar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Proje</th>
                        <th>Kontrol Türü</th>
                        <th>Tarih</th>
                        <th>Sorumlu</th>
                        <th>Durum</th>
                        <th>Sonuç</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['project_name'] ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $row['control_type'])) ?></td>
                        <td><?= date('d.m.Y', strtotime($row['control_date'])) ?></td>
                        <td><?= $row['responsible'] ?></td>
                        <td>
                            <span class="badge badge-<?= 
                                $row['status'] == 'approved' ? 'success' : 
                                ($row['status'] == 'non_conformity' ? 'danger' : 'warning') 
                            ?>">
                                <?= ucfirst(str_replace('_', ' ', $row['status'])) ?>
                            </span>
                        </td>
                        <td><?= substr($row['results'], 0, 50) ?>...</td>
                        <td>
                            <a href="view_qc.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Detay</a>
                            <a href="edit_qc.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Düzenle</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>