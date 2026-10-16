import 'package:flutter/material.dart';
import 'package:pos_mobile/core/theme/app_colors.dart';

abstract final class AppDecorations {
  static const double radiusSmall = 8.0;
  static const double radiusMedium = 12.0;
  static const double radiusLarge = 16.0;

  static final BorderRadius borderRadiusSmall = BorderRadius.circular(radiusSmall);
  static final BorderRadius borderRadiusMedium = BorderRadius.circular(radiusMedium);
  static final BorderRadius borderRadiusLarge = BorderRadius.circular(radiusLarge);

  /// Standard elevated/flat card container decoration with 1px subtle border
  static BoxDecoration cardDecoration(
    BuildContext context, {
    Color? backgroundColor,
    Color? borderColor,
  }) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return BoxDecoration(
      color: backgroundColor ?? (isDark ? AppColors.surfaceDark : AppColors.surfaceLight),
      borderRadius: borderRadiusMedium,
      border: Border.all(
        color: borderColor ?? (isDark ? AppColors.borderDark : AppColors.borderLight),
        width: 1,
      ),
      boxShadow: [
        BoxShadow(
          color: Colors.black.withValues(alpha: isDark ? 0.2 : 0.04),
          blurRadius: 8,
          offset: const Offset(0, 2),
        ),
      ],
    );
  }

  /// Subtle container decoration for top banners, quick actions, or grouped sections
  static BoxDecoration bannerDecoration(
    BuildContext context, {
    Color? backgroundColor,
    Color? borderColor,
  }) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return BoxDecoration(
      color: backgroundColor ?? (isDark ? AppColors.surfaceVariantDark : AppColors.primaryContainer),
      borderRadius: borderRadiusMedium,
      border: Border.all(
        color: borderColor ?? (isDark ? AppColors.borderDark : AppColors.primaryLight.withValues(alpha: 0.25)),
        width: 1,
      ),
    );
  }

  /// Reusable status chip or code pill decoration
  static BoxDecoration chipDecoration(
    BuildContext context, {
    required Color backgroundColor,
    Color? borderColor,
  }) {
    return BoxDecoration(
      color: backgroundColor,
      borderRadius: borderRadiusSmall,
      border: borderColor != null ? Border.all(color: borderColor, width: 1) : null,
    );
  }
}
