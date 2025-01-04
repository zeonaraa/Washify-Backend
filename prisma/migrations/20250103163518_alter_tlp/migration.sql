/*
  Warnings:

  - You are about to alter the column `qty` on the `tb_detail_transaksi` table. The data in that column could be lost. The data in that column will be cast from `Decimal(10,0)` to `Decimal`.

*/
-- DropForeignKey
ALTER TABLE `tb_detail_transaksi` DROP FOREIGN KEY `tb_detail_transaksi_id_paket_fkey`;

-- DropForeignKey
ALTER TABLE `tb_detail_transaksi` DROP FOREIGN KEY `tb_detail_transaksi_id_transaksi_fkey`;

-- DropForeignKey
ALTER TABLE `tb_pakets` DROP FOREIGN KEY `tb_pakets_id_outlet_fkey`;

-- DropForeignKey
ALTER TABLE `tb_transaksi` DROP FOREIGN KEY `tb_transaksi_id_member_fkey`;

-- DropForeignKey
ALTER TABLE `tb_transaksi` DROP FOREIGN KEY `tb_transaksi_id_outlet_fkey`;

-- DropForeignKey
ALTER TABLE `tb_transaksi` DROP FOREIGN KEY `tb_transaksi_id_user_fkey`;

-- DropForeignKey
ALTER TABLE `tb_users` DROP FOREIGN KEY `tb_users_id_outlet_fkey`;

-- DropIndex
DROP INDEX `tb_detail_transaksi_id_paket_fkey` ON `tb_detail_transaksi`;

-- DropIndex
DROP INDEX `tb_detail_transaksi_id_transaksi_fkey` ON `tb_detail_transaksi`;

-- DropIndex
DROP INDEX `tb_pakets_id_outlet_fkey` ON `tb_pakets`;

-- DropIndex
DROP INDEX `tb_transaksi_id_member_fkey` ON `tb_transaksi`;

-- DropIndex
DROP INDEX `tb_transaksi_id_outlet_fkey` ON `tb_transaksi`;

-- DropIndex
DROP INDEX `tb_transaksi_id_user_fkey` ON `tb_transaksi`;

-- DropIndex
DROP INDEX `tb_users_id_outlet_fkey` ON `tb_users`;

-- AlterTable
ALTER TABLE `tb_detail_transaksi` MODIFY `qty` DECIMAL NOT NULL;

-- AlterTable
ALTER TABLE `tb_outlets` MODIFY `tlp` VARCHAR(191) NOT NULL;

-- AlterTable
ALTER TABLE `tb_transaksi` MODIFY `tgl_bayar` DATETIME(3) NULL;

-- AddForeignKey
ALTER TABLE `tb_users` ADD CONSTRAINT `tb_users_id_outlet_fkey` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_pakets` ADD CONSTRAINT `tb_pakets_id_outlet_fkey` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_transaksi` ADD CONSTRAINT `tb_transaksi_id_outlet_fkey` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_transaksi` ADD CONSTRAINT `tb_transaksi_id_member_fkey` FOREIGN KEY (`id_member`) REFERENCES `tb_members`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_transaksi` ADD CONSTRAINT `tb_transaksi_id_user_fkey` FOREIGN KEY (`id_user`) REFERENCES `tb_users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_detail_transaksi` ADD CONSTRAINT `tb_detail_transaksi_id_transaksi_fkey` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tb_detail_transaksi` ADD CONSTRAINT `tb_detail_transaksi_id_paket_fkey` FOREIGN KEY (`id_paket`) REFERENCES `tb_pakets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
