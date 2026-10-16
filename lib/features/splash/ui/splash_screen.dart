import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/core/config/app_routes.dart';
import 'package:pos_mobile/features/splash/ui/cubit/splash_cubit.dart';
import 'package:pos_mobile/screens/common/primary_action_button.dart';

class SplashScreen extends StatelessWidget {
  const SplashScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return BlocConsumer<SplashCubit, SplashState>(
      listener: (context, state) {
        state.whenOrNull(
          success: () {
            Get.offAllNamed(AppRoutes.home);
          },
        );
      },
      builder: (context, state) {
        return Scaffold(
          body: Center(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: theme.colorScheme.primaryContainer,
                      shape: BoxShape.circle,
                    ),
                    child: Icon(
                      Icons.point_of_sale_rounded,
                      size: 64,
                      color: theme.colorScheme.primary,
                    ),
                  ),
                  const SizedBox(height: 20),
                  Text(
                    'POS Mobile',
                    style: theme.textTheme.headlineMedium?.copyWith(
                      fontWeight: FontWeight.bold,
                      letterSpacing: -0.5,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    'Smart Retail & Checkout System',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                    ),
                  ),
                  const SizedBox(height: 36),
                  ...state.maybeWhen(
                    error: (message) => [
                      Text(
                        message,
                        textAlign: TextAlign.center,
                        style: TextStyle(color: theme.colorScheme.error),
                      ),
                      const SizedBox(height: 16),
                      PrimaryActionButton(
                        label: 'Retry Connection',
                        icon: const Icon(Icons.refresh, color: Colors.white, size: 18),
                        onPressed: () => context.read<SplashCubit>().syncProducts(),
                      ),
                      const SizedBox(height: 8),
                      TextButton(
                        onPressed: () => Get.offNamed(AppRoutes.home),
                        child: const Text('Continue Offline'),
                      ),
                    ],
                    orElse: () => [
                      const CircularProgressIndicator(),
                      const SizedBox(height: 16),
                      Text(
                        'Syncing catalog...',
                        style: theme.textTheme.bodyMedium?.copyWith(
                          color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
