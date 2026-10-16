import 'package:flutter/material.dart';

abstract final class AppColors {
  // Brand Primary - Modern Forest Teal & Mint
  static const Color primary = Color(0xFF0F766E);
  static const Color primaryDark = Color(0xFF0D5C46);
  static const Color primaryLight = Color(0xFF14B8A6);
  static const Color primaryContainer = Color(0xFFCCFBF1);
  static const Color onPrimaryContainer = Color(0xFF115E59);

  // Secondary & Accents
  static const Color secondary = Color(0xFF0284C7);
  static const Color secondaryContainer = Color(0xFFE0F2FE);
  static const Color onSecondaryContainer = Color(0xFF0369A1);
  static const Color accent = Color(0xFF10B981);

  // Neutral - Light Mode
  static const Color backgroundLight = Color(0xFFF8FAFC);
  static const Color surfaceLight = Color(0xFFFFFFFF);
  static const Color surfaceVariantLight = Color(0xFFF1F5F9);
  static const Color borderLight = Color(0xFFE2E8F0);
  static const Color borderSubtleLight = Color(0xFFF1F5F9);
  static const Color textPrimaryLight = Color(0xFF0F172A);
  static const Color textSecondaryLight = Color(0xFF475569);
  static const Color textTertiaryLight = Color(0xFF94A3B8);

  // Neutral - Dark Mode
  static const Color backgroundDark = Color(0xFF0F172A);
  static const Color surfaceDark = Color(0xFF1E293B);
  static const Color surfaceVariantDark = Color(0xFF334155);
  static const Color borderDark = Color(0xFF334155);
  static const Color borderSubtleDark = Color(0xFF1E293B);
  static const Color textPrimaryDark = Color(0xFFF8FAFC);
  static const Color textSecondaryDark = Color(0xFF94A3B8);
  static const Color textTertiaryDark = Color(0xFF64748B);

  // Semantic Status Colors
  static const Color success = Color(0xFF10B981);
  static const Color successContainer = Color(0xFFD1FAE5);
  static const Color onSuccessContainer = Color(0xFF065F46);

  static const Color warning = Color(0xFFF59E0B);
  static const Color warningContainer = Color(0xFFFEF3C7);
  static const Color onWarningContainer = Color(0xFF92400E);

  static const Color error = Color(0xFFEF4444);
  static const Color errorContainer = Color(0xFFFEE2E2);
  static const Color onErrorContainer = Color(0xFF991B1B);

  static const Color info = Color(0xFF3B82F6);
  static const Color infoContainer = Color(0xFFDBEAFE);
  static const Color onInfoContainer = Color(0xFF1E40AF);
}
