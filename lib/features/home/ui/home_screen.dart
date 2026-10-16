import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/core/config/app_routes.dart';
import 'package:pos_mobile/core/theme/app_decorations.dart';
import 'package:pos_mobile/features/home/ui/cubit/home_cubit.dart';
import 'package:pos_mobile/features/home/ui/widgets/product_card.dart';
import 'package:pos_mobile/screens/common/primary_action_button.dart';
import 'package:pos_mobile/screens/common/section_header.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('POS Cashier'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            tooltip: 'Sync Products',
            onPressed: () => context.read<HomeCubit>().getProducts(),
          ),
        ],
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Top Section: Quick Bills Bar using AppDecorations
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
            child: InkWell(
              borderRadius: AppDecorations.borderRadiusMedium,
              onTap: () => Get.toNamed(AppRoutes.bills),
              child: Container(
                decoration: AppDecorations.bannerDecoration(context),
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 14,
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(
                            color: theme.colorScheme.primary.withValues(
                              alpha: 0.12,
                            ),
                            borderRadius: AppDecorations.borderRadiusSmall,
                          ),
                          child: Icon(
                            Icons.receipt_long_rounded,
                            color: theme.colorScheme.primary,
                            size: 22,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Bills & Sales History',
                              style: theme.textTheme.titleMedium?.copyWith(
                                fontWeight: FontWeight.bold,
                                color: theme.colorScheme.onSurface,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              'Manage and create customer bills',
                              style: theme.textTheme.bodySmall?.copyWith(
                                color: theme.colorScheme.onSurface.withValues(
                                  alpha: 0.7,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    Icon(
                      Icons.arrow_forward_ios_rounded,
                      size: 16,
                      color: theme.colorScheme.primary,
                    ),
                  ],
                ),
              ),
            ),
          ),

          const Divider(height: 1),

          // Product Grid Section
          Expanded(
            child: BlocBuilder<HomeCubit, HomeState>(
              builder: (context, state) {
                return state.when(
                  initial: () => const SizedBox.shrink(),
                  loading: () =>
                      const Center(child: CircularProgressIndicator()),
                  error: (message) => Center(
                    child: Padding(
                      padding: const EdgeInsets.all(24.0),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            Icons.error_outline_rounded,
                            size: 48,
                            color: theme.colorScheme.error,
                          ),
                          const SizedBox(height: 12),
                          Text(
                            message,
                            textAlign: TextAlign.center,
                            style: TextStyle(color: theme.colorScheme.error),
                          ),
                          const SizedBox(height: 16),
                          PrimaryActionButton(
                            label: 'Retry Connection',
                            icon: const Icon(
                              Icons.refresh,
                              color: Colors.white,
                              size: 18,
                            ),
                            onPressed: () =>
                                context.read<HomeCubit>().getProducts(),
                          ),
                        ],
                      ),
                    ),
                  ),
                  loaded: (products) {
                    if (products.isEmpty) {
                      return Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              Icons.inventory_2_outlined,
                              size: 64,
                              color: theme.colorScheme.outline,
                            ),
                            const SizedBox(height: 12),
                            Text(
                              'No products found',
                              style: TextStyle(
                                color: theme.colorScheme.onSurface.withValues(
                                  alpha: 0.6,
                                ),
                                fontSize: 16,
                              ),
                            ),
                          ],
                        ),
                      );
                    }

                    return RefreshIndicator(
                      onRefresh: () => context.read<HomeCubit>().getProducts(),
                      child: GridView.builder(
                        padding: const EdgeInsets.symmetric(vertical: 8),
                        gridDelegate:
                            const SliverGridDelegateWithFixedCrossAxisCount(
                              crossAxisCount: 2,
                              childAspectRatio: 0.75,
                              crossAxisSpacing: 0,
                              mainAxisSpacing: 5,
                            ),
                        itemCount: products.length,
                        itemBuilder: (context, index) {
                          final product = products[index];
                          return ProductCard(product: product);
                        },
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
