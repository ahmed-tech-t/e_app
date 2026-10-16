import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/features/bills/domain/models/price_type.dart';
import 'package:pos_mobile/features/bills/domain/services/bill_pdf_service.dart';
import 'package:pos_mobile/features/bills/ui/cubit/bills_cubit.dart';
import 'package:pos_mobile/features/bills/ui/cubit/create_bill_cubit.dart';
import 'package:pos_mobile/features/bills/ui/widgets/bill_preview_dialog.dart';
import 'package:pos_mobile/features/bills/ui/widgets/qr_scanner_dialog.dart';

class CreateBillScreen extends StatefulWidget {
  const CreateBillScreen({super.key});

  @override
  State<CreateBillScreen> createState() => _CreateBillScreenState();
}

class _CreateBillScreenState extends State<CreateBillScreen> {
  final _formKey = GlobalKey<FormState>();
  final _qrController = TextEditingController();
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _discountController = TextEditingController(text: '0');

  @override
  void dispose() {
    _qrController.dispose();
    _nameController.dispose();
    _phoneController.dispose();
    _discountController.dispose();
    super.dispose();
  }

  void _onScanOrEnterCode(String code) {
    if (code.trim().isEmpty) return;
    final cubit = context.read<CreateBillCubit>();
    final found = cubit.addProductByCode(code);
    if (found) {
      _qrController.clear();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Product added successfully!'),
          duration: Duration(milliseconds: 900),
          backgroundColor: Colors.green,
        ),
      );
    }
  }

  Future<void> _openCameraScanner() async {
    final scannedCode = await showDialog<String>(
      context: context,
      builder: (_) => const QrScannerDialog(),
    );
    if (scannedCode != null && scannedCode.isNotEmpty) {
      _onScanOrEnterCode(scannedCode);
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('New Bill (Cashier)'),
      ),
      body: BlocConsumer<CreateBillCubit, CreateBillState>(
        listener: (context, state) {
          if (state.errorMessage != null) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(state.errorMessage!),
                backgroundColor: Colors.red,
              ),
            );
          }
        },
        builder: (context, state) {
          final cubit = context.read<CreateBillCubit>();
          final subtotal = state.items.fold<double>(
            0.0,
            (sum, item) => sum + item.getSubtotal(state.priceType),
          );
          final grandTotal = (subtotal - state.discount).clamp(0.0, double.infinity);

          return Column(
            children: [
              // 1. Customer, Discount & Price Type Header
              Container(
                padding: const EdgeInsets.all(12),
                color: theme.colorScheme.surfaceContainerHighest.withValues(alpha: 0.4),
                child: Form(
                  key: _formKey,
                  child: Column(
                    children: [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            flex: 3,
                            child: TextFormField(
                              controller: _nameController,
                              decoration: const InputDecoration(
                                labelText: 'Customer Name *',
                                prefixIcon: Icon(Icons.person_outline, size: 20),
                                isDense: true,
                                border: OutlineInputBorder(),
                              ),
                              validator: (value) {
                                final text = value?.trim() ?? '';
                                if (text.isEmpty) {
                                  return 'Name is required';
                                }
                                if (text.length > 255) {
                                  return 'Max 255 characters';
                                }
                                return null;
                              },
                              onChanged: cubit.setCustomerName,
                            ),
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            flex: 3,
                            child: TextFormField(
                              controller: _phoneController,
                              keyboardType: TextInputType.phone,
                              decoration: const InputDecoration(
                                labelText: 'Phone (optional)',
                                prefixIcon: Icon(Icons.phone_outlined, size: 20),
                                isDense: true,
                                border: OutlineInputBorder(),
                              ),
                              validator: (value) {
                                final text = value?.trim() ?? '';
                                if (text.isNotEmpty) {
                                  final regex = RegExp(r'^[0-9+\s\-]{6,20}$');
                                  if (!regex.hasMatch(text)) {
                                    return 'Invalid phone';
                                  }
                                }
                                return null;
                              },
                              onChanged: cubit.setCustomerPhone,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 10),
                      Row(
                        children: [
                          // Discount field
                          SizedBox(
                            width: 140,
                            child: TextFormField(
                              controller: _discountController,
                              keyboardType: const TextInputType.numberWithOptions(decimal: true),
                              decoration: const InputDecoration(
                                labelText: 'Discount %',
                                prefixIcon: Icon(Icons.discount_outlined, size: 20),
                                suffixText: '%',
                                isDense: true,
                                border: OutlineInputBorder(),
                              ),
                              validator: (value) {
                                final text = value?.trim() ?? '';
                                if (text.isNotEmpty) {
                                  final numValue = double.tryParse(text);
                                  if (numValue == null || numValue < 0 || numValue > 100) {
                                    return '0 - 100%';
                                  }
                                }
                                return null;
                              },
                              onChanged: (val) {
                                final discountVal = double.tryParse(val) ?? 0.0;
                                cubit.setDiscount(discountVal);
                              },
                            ),
                          ),
                          const SizedBox(width: 10),
                          // Price Type selector
                          Expanded(
                            child: SegmentedButton<PriceType>(
                              segments: const [
                                ButtonSegment(
                                  value: PriceType.retail,
                                  label: Text('Retail'),
                                  icon: Icon(Icons.storefront_outlined, size: 18),
                                ),
                                ButtonSegment(
                                  value: PriceType.wholesale,
                                  label: Text('Wholesale'),
                                  icon: Icon(Icons.warehouse_outlined, size: 18),
                                ),
                              ],
                              selected: {state.priceType},
                              onSelectionChanged: (set) => cubit.setPriceType(set.first),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),

              // 2. QR / Barcode Scan Input Bar
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                child: Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _qrController,
                        decoration: InputDecoration(
                          labelText: 'Scan QR / Barcode or Enter Code',
                          hintText: 'e.g. 123456',
                          prefixIcon: IconButton(
                            icon: const Icon(Icons.camera_alt_outlined),
                            tooltip: 'Scan with Camera',
                            onPressed: _openCameraScanner,
                          ),
                          suffixIcon: IconButton(
                            icon: const Icon(Icons.arrow_forward_rounded),
                            tooltip: 'Add by Code',
                            onPressed: () => _onScanOrEnterCode(_qrController.text),
                          ),
                          border: const OutlineInputBorder(),
                        ),
                        onSubmitted: _onScanOrEnterCode,
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton.filledTonal(
                      icon: const Icon(Icons.qr_code_scanner_rounded),
                      tooltip: 'Open Camera Scanner',
                      onPressed: _openCameraScanner,
                    ),
                  ],
                ),
              ),

              const Divider(height: 1),

              // 3. Scanned Product Items List
              Expanded(
                child: state.items.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.qr_code_2_rounded, size: 64, color: Colors.grey[400]),
                            const SizedBox(height: 8),
                            const Text(
                              'Scan barcode or enter code to add items',
                              style: TextStyle(color: Colors.grey, fontSize: 15),
                            ),
                          ],
                        ),
                      )
                    : ListView.separated(
                        padding: const EdgeInsets.all(12),
                        itemCount: state.items.length,
                        separatorBuilder: (context, index) => const SizedBox(height: 8),
                        itemBuilder: (context, index) {
                          final item = state.items[index];
                          final product = item.product;
                          final unitPrice = item.getPrice(state.priceType);
                          final displayName = (product.nameEn?.isNotEmpty ?? false)
                              ? product.nameEn!
                              : (product.nameAr ?? 'Product');

                          return Card(
                            elevation: 1,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(8.0),
                              child: Row(
                                children: [
                                  // Product Image
                                  Container(
                                    width: 50,
                                    height: 50,
                                    decoration: BoxDecoration(
                                      color: Colors.grey[200],
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    clipBehavior: Clip.antiAlias,
                                    child: product.image != null && product.image!.isNotEmpty
                                        ? CachedNetworkImage(
                                            imageUrl: product.image!,
                                            fit: BoxFit.cover,
                                            placeholder: (context, url) => const Center(
                                              child: SizedBox(
                                                width: 18,
                                                height: 18,
                                                child: CircularProgressIndicator(strokeWidth: 2),
                                              ),
                                            ),
                                            errorWidget: (context, url, error) =>
                                                const Icon(Icons.inventory_2_outlined),
                                          )
                                        : const Icon(Icons.inventory_2_outlined),
                                  ),
                                  const SizedBox(width: 12),
                                  // Details
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          displayName,
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                          style: const TextStyle(fontWeight: FontWeight.bold),
                                        ),
                                        Text(
                                          'ID: ${product.id ?? '-'} | Code: ${product.code ?? '-'}',
                                          style: TextStyle(color: Colors.grey[600], fontSize: 12),
                                        ),
                                        Text(
                                          '\$${unitPrice.toStringAsFixed(2)} each',
                                          style: TextStyle(
                                            color: theme.colorScheme.primary,
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                  // Quantity Counter
                                  Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      IconButton(
                                        icon: const Icon(Icons.remove_circle_outline, size: 20),
                                        onPressed: () => cubit.updateQuantity(
                                          index,
                                          item.quantity - 1,
                                        ),
                                      ),
                                      Text(
                                        '${item.quantity}',
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 16,
                                        ),
                                      ),
                                      IconButton(
                                        icon: const Icon(Icons.add_circle_outline, size: 20),
                                        onPressed: () => cubit.updateQuantity(
                                          index,
                                          item.quantity + 1,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
              ),

              // 4. Bottom Checkout Bar
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: BoxDecoration(
                  color: theme.colorScheme.surface,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.08),
                      blurRadius: 8,
                      offset: const Offset(0, -2),
                    ),
                  ],
                ),
                child: SafeArea(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            '${state.items.length} items',
                            style: TextStyle(color: Colors.grey[600], fontSize: 13),
                          ),
                          Text(
                            '\$${grandTotal.toStringAsFixed(2)}',
                            style: theme.textTheme.headlineSmall?.copyWith(
                              fontWeight: FontWeight.bold,
                              color: theme.colorScheme.primary,
                            ),
                          ),
                        ],
                      ),
                      ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                        onPressed: state.isPreviewLoading || state.isSubmitting
                            ? null
                            : () async {
                                if (!_formKey.currentState!.validate()) {
                                  return;
                                }
                                final preview = await cubit.previewBill();
                                if (preview != null && context.mounted) {
                                  showDialog<void>(
                                    context: context,
                                    barrierDismissible: false,
                                    builder: (dialogCtx) => BlocBuilder<CreateBillCubit, CreateBillState>(
                                      bloc: cubit,
                                      builder: (context, currentState) {
                                        return BillPreviewDialog(
                                          bill: preview,
                                          products: currentState.items.map((i) => i.product).toList(),
                                          isSubmitting: currentState.isSubmitting,
                                          onConfirm: () async {
                                            final createdBill = await cubit.submitBill();
                                            if (createdBill != null && dialogCtx.mounted) {
                                              Navigator.of(dialogCtx).pop();
                                              Get.find<BillsCubit>().fetchBills();
                                              Get.back();
                                              Get.snackbar(
                                                'Success',
                                                'Bill #${createdBill.id ?? ''} completed successfully!',
                                                backgroundColor: Colors.green,
                                                colorText: Colors.white,
                                              );
                                              // Prompt to print or view PDF receipt
                                              if (context.mounted) {
                                                await BillPdfService.printOrPreviewReceipt(
                                                  context,
                                                  createdBill,
                                                  products: currentState.items.map((i) => i.product).toList(),
                                                );
                                              }
                                            }
                                          },
                                        );
                                      },
                                    ),
                                  );
                                }
                              },
                        icon: state.isPreviewLoading
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child: CircularProgressIndicator(strokeWidth: 2),
                              )
                            : const Icon(Icons.preview_outlined),
                        label: Text(state.isPreviewLoading ? 'Calculating...' : 'Preview Bill'),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
