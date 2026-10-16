import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/core/config/app_routes.dart';
import 'package:pos_mobile/features/bills/ui/cubit/bills_cubit.dart';
import 'package:pos_mobile/features/bills/ui/widgets/bill_card.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';
import 'package:pos_mobile/screens/common/primary_action_button.dart';

class BillsScreen extends StatelessWidget {
  const BillsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Bills & Invoices'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            tooltip: 'Refresh Bills',
            onPressed: () => context.read<BillsCubit>().fetchBills(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => Get.toNamed(AppRoutes.createBill),
        icon: const Icon(Icons.add),
        label: const Text('New Bill'),
      ),
      body: BlocBuilder<BillsCubit, BillsState>(
        builder: (context, state) {
          return state.when(
            initial: () => const SizedBox.shrink(),
            loading: () => const Center(child: CircularProgressIndicator()),
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
                      label: 'Retry',
                      icon: const Icon(Icons.refresh, color: Colors.white, size: 18),
                      onPressed: () => context.read<BillsCubit>().fetchBills(),
                    ),
                  ],
                ),
              ),
            ),
            loaded: (bills) {
              if (bills.isEmpty) {
                return Center(
                  child: Padding(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          Icons.receipt_long_outlined,
                          size: 64,
                          color: theme.colorScheme.outline,
                        ),
                        const SizedBox(height: 12),
                        Text(
                          'No bills recorded yet',
                          style: TextStyle(
                            color: theme.colorScheme.onSurface.withValues(alpha: 0.6),
                            fontSize: 16,
                          ),
                        ),
                        const SizedBox(height: 16),
                        PrimaryActionButton(
                          label: 'Create First Bill',
                          icon: const Icon(Icons.add, color: Colors.white, size: 18),
                          onPressed: () => Get.toNamed(AppRoutes.createBill),
                        ),
                      ],
                    ),
                  ),
                );
              }

              final products = Get.find<ProductRepository>().getCachedProducts();

              return RefreshIndicator(
                onRefresh: () => context.read<BillsCubit>().fetchBills(),
                child: ListView.builder(
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  itemCount: bills.length,
                  itemBuilder: (context, index) {
                    final bill = bills[index];
                    return BillCard(
                      bill: bill,
                      products: products,
                    );
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }
}
