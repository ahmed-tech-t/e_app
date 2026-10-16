import 'package:flutter/material.dart';

class SectionHeader extends StatelessWidget {
  final String title;
  final String? subtitle;
  final Widget? trailing;
  final IconData? icon;
  final EdgeInsetsGeometry padding;

  const SectionHeader({
    super.key,
    required this.title,
    this.subtitle,
    this.trailing,
    this.icon,
    this.padding = const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Padding(
      padding: padding,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Row(
            children: [
              if (icon != null) ...[
                Icon(icon, size: 20, color: theme.colorScheme.primary),
                const SizedBox(width: 8),
              ],
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    title,
                    style: theme.textTheme.titleMedium?.copyWith(
                      fontWeight: FontWeight.bold,
                      letterSpacing: -0.2,
                    ),
                  ),
                  if (subtitle != null) ...[
                    const SizedBox(height: 2),
                    Text(
                      subtitle!,
                      style: theme.textTheme.bodySmall?.copyWith(
                        color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                      ),
                    ),
                  ],
                ],
              ),
            ],
          ),
          ?trailing,
        ],
      ),
    );
  }
}

/// Widget Preview
Widget previewSectionHeader() {
  return const MaterialApp(
    home: Scaffold(
      body: Center(
        child: SectionHeader(
          title: 'Products Catalog',
          subtitle: 'Available inventory',
          icon: Icons.inventory_2_outlined,
        ),
      ),
    ),
  );
}
