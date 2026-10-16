import 'package:flutter/material.dart';

class PrimaryActionButton extends StatelessWidget {
  final String label;
  final VoidCallback? onPressed;
  final Widget? icon;
  final bool isLoading;
  final bool isFullWidth;
  final double height;
  final Color? backgroundColor;
  final Color? foregroundColor;
  final BorderRadius? borderRadius;

  const PrimaryActionButton({
    super.key,
    required this.label,
    required this.onPressed,
    this.icon,
    this.isLoading = false,
    this.isFullWidth = false,
    this.height = 50.0,
    this.backgroundColor,
    this.foregroundColor,
    this.borderRadius,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final effectiveBg = backgroundColor ?? theme.colorScheme.primary;
    final effectiveFg = foregroundColor ?? theme.colorScheme.onPrimary;
    final radius = borderRadius ?? BorderRadius.circular(10);

    Widget content = Row(
      mainAxisSize: isFullWidth ? MainAxisSize.max : MainAxisSize.min,
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        if (isLoading) ...[
          SizedBox(
            width: 20,
            height: 20,
            child: CircularProgressIndicator(
              strokeWidth: 2.2,
              valueColor: AlwaysStoppedAnimation<Color>(effectiveFg),
            ),
          ),
          const SizedBox(width: 10),
        ] else if (icon != null) ...[
          icon!,
          const SizedBox(width: 8),
        ],
        Text(
          label,
          style: TextStyle(
            color: effectiveFg,
            fontSize: 15,
            fontWeight: FontWeight.w600,
            letterSpacing: 0.2,
          ),
        ),
      ],
    );

    final button = Material(
      color: onPressed != null && !isLoading ? effectiveBg : effectiveBg.withValues(alpha: 0.5),
      borderRadius: radius,
      child: InkWell(
        borderRadius: radius,
        onTap: isLoading ? null : onPressed,
        child: Container(
          height: height,
          padding: const EdgeInsets.symmetric(horizontal: 20),
          alignment: Alignment.center,
          child: content,
        ),
      ),
    );

    if (isFullWidth) {
      return SizedBox(
        width: double.infinity,
        child: button,
      );
    }

    return button;
  }
}

/// Widget Preview
Widget previewPrimaryActionButton() {
  return MaterialApp(
    home: Scaffold(
      body: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            PrimaryActionButton(
              label: 'Submit Bill',
              icon: const Icon(Icons.check, color: Colors.white),
              onPressed: () {},
            ),
            const SizedBox(height: 12),
            PrimaryActionButton(
              label: 'Loading...',
              isLoading: true,
              onPressed: () {},
            ),
          ],
        ),
      ),
    ),
  );
}
