import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/core/config/app_routes.dart';
import 'package:pos_mobile/features/bills/ui/cubit/bills_cubit.dart';
import 'package:pos_mobile/features/bills/ui/widgets/bill_card.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

class BillsScreen extends StatelessWidget {
  const BillsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Bills & Invoices'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
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
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(Icons.error_outline, size: 48, color: Colors.red),
                    const SizedBox(height: 12),
                    Text(message, textAlign: TextAlign.center),
                    const SizedBox(height: 16),
                    ElevatedButton.icon(
                      onPressed: () => context.read<BillsCubit>().fetchBills(),
                      icon: const Icon(Icons.refresh),
                      label: const Text('Retry'),
                    ),
                  ],
                ),
              ),
            ),
            loaded: (bills) {
              if (bills.isEmpty) {
                return Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(
                        Icons.receipt_long_outlined,
                        size: 64,
                        color: Colors.grey[400],
                      ),
                      const SizedBox(height: 12),
                      const Text(
                        'No bills recorded yet',
                        style: TextStyle(color: Colors.grey, fontSize: 16),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton.icon(
                        onPressed: () => Get.toNamed(AppRoutes.createBill),
                        icon: const Icon(Icons.add),
                        label: const Text('Create First Bill'),
                      ),
                    ],
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
