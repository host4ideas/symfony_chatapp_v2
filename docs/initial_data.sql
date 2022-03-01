INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES ('0', 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$N7T/1x3XViAHKyggNX4i.O2qDIDXcjZcUEn2iGhl/udMtyQ78XUci');
INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES ('1', 'example1@example1.com', '[\"ROLE_USER\"]', '$2y$13$y5FfKsg4s8TyFg26J5xr4e/qp6.p28uqLUmMzREeaW1un2Wmdqt9S');
INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES ('2', 'example2@example2.com', '[\"ROLE_USER\"]', '$2y$13$y5FfKsg4s8TyFg26J5xr4e/qp6.p28uqLUmMzREeaW1un2Wmdqt9S');
INSERT INTO `message` (`id`, `message`, `date`, `is_read`, `sender`, `receiver`) VALUES (NULL, 'Test from example1@example1.com to example2@example2.com', '2022-03-01 12:33:09', '0', '1', '2');
INSERT INTO `message` (`id`, `message`, `date`, `is_read`, `sender`, `receiver`) VALUES (NULL, 'Test from example2@example2.com to example1@example1.com', '2022-03-01 12:33:09', '0', '2', '1');
