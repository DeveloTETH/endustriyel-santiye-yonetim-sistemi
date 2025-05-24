<?php
include('../includes/header.php');
include('../config.php');

// Çelik montaj kayıtlarını getirme
$query = "SELECT sa.*, p.project_name, per.full_name as responsible 
          FROM steel_assembly sa
          JOIN projects p ON sa.project_id = p.id
          LEFT JOIN personnel per ON sa.responsible_person_id = per.id";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h2>Çelik Montaj Takibi</h2>
    <a href="add_steel_assembly.php" class="btn btn-primary mb-3">Yeni Kayıt Ekle</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Proje</th>
                <th>Çizim No</th>
                <th>Eleman Kodu</th>
                <th>Montaj Tarihi</th>
                <th>Durum</th>
                <th>Sorumlu</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['project_name'] ?></td>
                <td><?= $row['drawing_no'] ?></td>
                <td><?= $row['element_code'] ?></td>
                <td><?= date('d.m.Y', strtotime($row['assembly_date'])) ?></td>
                <td>
                    <span class="badge badge-<?= 
                        $row['status'] == 'completed' ? 'success' : 
                        ($row['status'] == 'in_progress' ? 'warning' : 'secondary') 
                    ?>">
                        <?= ucfirst(str_replace('_', ' ', $row['status'])) ?>
                    </span>
                </td>
                <td><?= $row['responsible'] ?></td>
                <td>
                    <a href="edit_steel_assembly.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Düzenle</a>
                    <a href="view_steel_assembly.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Detay</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>