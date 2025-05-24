<?php
include('../includes/header.php');
include('../config.php');

// Kaynak işlemlerini getirme
$query = "SELECT wo.*, pa.line_no, pa.spec, per.full_name as welder
          FROM welding_operations wo
          JOIN pipe_assembly pa ON wo.pipe_assembly_id = pa.id
          JOIN personnel per ON wo.welder_id = per.id
          ORDER BY wo.welding_date DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h2>Kaynak İşlemleri Takibi</h2>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="add_welding.php" class="btn btn-primary">Yeni Kaynak Kaydı</a>
        </div>
        <div class="col-md-6 text-right">
            <a href="welding_reports.php" class="btn btn-secondary">Raporlar</a>
        </div>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Hat No</th>
                <th>Kaynak No</th>
                <th>Kaynakçı</th>
                <th>Tarih</th>
                <th>Yöntem</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['line_no'] ?></td>
                <td><?= $row['weld_no'] ?></td>
                <td><?= $row['welder'] ?></td>
                <td><?= date('d.m.Y', strtotime($row['welding_date'])) ?></td>
                <td><?= $row['welding_method'] ?></td>
                <td>
                    <span class="badge badge-<?= 
                        $row['status'] == 'approved' ? 'success' : 
                        ($row['status'] == 'rejected' ? 'danger' : 'warning') 
                    ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="view_welding.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Detay</a>
                    <?php if($row['status'] != 'approved'): ?>
                        <a href="inspect_welding.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Kontrol Et</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>