import 'package:flutter/material.dart';
import 'package:pos_mobile/core/theme/app_colors.dart';

abstract final class AppSnackBar {
  static void showSuccess(BuildContext context, String message, {Duration duration = const Duration(seconds: 2)}) {
    _show(
      context,
      message: message,
      backgroundColor: AppColors.success,
      icon: Icons.check_circle_outline_rounded,
      duration: duration,
    );
  }

  static void showError(BuildContext context, String message, {Duration duration = const Duration(seconds: 3)}) {
    _show(
      context,
      message: message,
      backgroundColor: AppColors.error,
      icon: Icons.error_outline_rounded,
      duration: duration,
    );
  }

  static void showInfo(BuildContext context, String message, {Duration duration = const Duration(seconds: 2)}) {
    _show(
      context,
      message: message,
      backgroundColor: AppColors.primary,
      icon: Icons.info_outline_rounded,
      duration: duration,
    );
  }

  static void _show(
    BuildContext context, {
    required String message,
    required Color backgroundColor,
    required IconData icon,
    required Duration duration,
  }) {
    ScaffoldMessenger.of(context).hideCurrentSnackBar();
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        behavior: SnackBarBehavior.floating,
        duration: duration,
        backgroundColor: backgroundColor,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        content: Row(
          children: [
            Icon(icon, color: Colors.white, size: 20),
            const SizedBox(width: 10),
            Expanded(
              child: Text(
                message,
                style: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.w500,
                  fontSize: 14,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
